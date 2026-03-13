<?php
// app/Http/Controllers/ParentsController.php

namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\User;
use App\Models\Student;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParentsController extends Controller
{
    /**
     * Display a listing of parents.
     */
    public function index()
    {
        // Get all parents with their associated users and students through the pivot table
        $parents = Parents::with(['user', 'students'])->get();
        
        // Get all students for the dropdown from the students table
        $students = Student::select('student_id', 'fname', 'lname', 'student_code')
                        ->where('status', 'active')
                        ->get();
        
        // Fix: Get users that are either:
        // 1. Already have role 'parent' OR
        // 2. Don't have a parent record yet
        $users = User::where('role', 'parent')
                    ->orWhereDoesntHave('parent') // This uses the relationship we just defined
                    ->get();
        
        // Get branches for potential use
        $branches = Branch::all();
        
        return view('parent', [
            'parents' => $parents,
            'students' => $students,
            'users' => $users,
            'branches' => $branches
        ]);
    }

    /**
     * Store a newly created parent.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $validated = $request->validate([
                'firstname' => 'required|string|max:170',
                'lastname' => 'required|string|max:170',
                'gender' => 'required|string',
                'address' => 'required|string|max:255',
                'relationship_note' => 'nullable|string',
                'phone' => 'nullable|string|max:13',
                'status' => 'nullable|in:active,inactive',
                'students' => 'nullable|array',
                'students.*' => 'exists:students,student_id',
                'create_user_account' => 'nullable|boolean',
                'email' => 'required_if:create_user_account,1|email|unique:users,email|nullable',
                'username' => 'required_if:create_user_account,1|string|min:3|unique:users,username|nullable',
                'password' => 'required_if:create_user_account,1|string|min:6|nullable',
                'mobile_no' => 'required_if:create_user_account,1|string|max:13|nullable',
            ]);

            // Handle user account creation if requested
            $userId = null;
            if ($request->has('create_user_account') && $request->create_user_account) {
                $user = User::create([
                    'branch_id' => 1, // Default branch, you might want to make this selectable
                    'role' => 'parent',
                    'fname' => $request->firstname,
                    'lname' => $request->lastname,
                    'username' => $request->username,
                    'email' => $request->email,
                    'mobile_no' => $request->mobile_no,
                    'password' => Hash::make($request->password),
                ]);
                $userId = $user->user_id;
            }

            // Create parent record
            $parent = Parents::create([
                'user_id' => $userId,
                'fname' => $request->firstname,
                'lname' => $request->lastname,
                'emergency_contact' => $request->phone,
                'relationship_note' => $request->relationship_note,
                'address' => $request->address,
                'id_verified_flag' => 0, // Default to not verified
                'status' => $request->status ?? 'active',
                'gender' => $request->gender,
                'created_at' => now(),
            ]);

            // Link students if any using the pivot table
            if ($request->has('students') && !empty($request->students)) {
                foreach ($request->students as $studentId) {
                    DB::table('parent_students')->insert([
                        'id' => $parent->id,
                        'student_id' => $studentId,
                        'relationship' => 'guardian', // Default, you might want to make this selectable
                        'is_primary' => 0
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('parents.index')
                ->with('success', 'Parent created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create parent: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create parent. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified parent.
     */
    public function show($id)
    {
        try {
            $parent = Parents::with(['user', 'students'])->findOrFail($id);
            
            return response()->json([
                'id' => $parent->id,
                'full_name' => $parent->fname . ' ' . $parent->lname,
                'fname' => $parent->fname,
                'lname' => $parent->lname,
                'address' => $parent->address,
                'emergency_contact' => $parent->emergency_contact,
                'relationship_note' => $parent->relationship_note,
                'status' => $parent->status,
                'gender' => $parent->gender ?? 'Not specified',
                'created_at' => $parent->created_at,
                'user' => $parent->user,
                'students' => $parent->students,
                'children_count' => $parent->students->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load parent: ' . $e->getMessage());
            return response()->json(['error' => 'Parent not found'], 404);
        }
    }

    /**
     * Update the specified parent.
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $parent = Parents::findOrFail($id);

            $validated = $request->validate([
                'firstname' => 'required|string|max:170',
                'lastname' => 'required|string|max:170',
                'gender' => 'required|string',
                'address' => 'required|string|max:255',
                'relationship_note' => 'nullable|string',
                'phone' => 'nullable|string|max:13',
                'status' => 'nullable|in:active,inactive',
                'students' => 'nullable|array',
                'students.*' => 'exists:students,student_id',
            ]);

            // Update parent record
            $parent->update([
                'fname' => $request->firstname,
                'lname' => $request->lastname,
                'emergency_contact' => $request->phone,
                'relationship_note' => $request->relationship_note,
                'address' => $request->address,
                'status' => $request->status ?? 'active',
                'gender' => $request->gender,
            ]);

            // Update student links in pivot table
            if ($request->has('students')) {
                // Delete existing links
                DB::table('parent_students')->where('id', $parent->id)->delete();
                
                // Add new links
                foreach ($request->students as $studentId) {
                    DB::table('parent_students')->insert([
                        'id' => $parent->id,
                        'student_id' => $studentId,
                        'relationship' => 'guardian',
                        'is_primary' => 0
                    ]);
                }
            } else {
                // If no students selected, delete all links
                DB::table('parent_students')->where('id', $parent->id)->delete();
            }

            DB::commit();

            return redirect()->route('parents.index')
                ->with('success', 'Parent updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update parent: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update parent.')
                ->withInput();
        }
    }

    /**
     * Remove the specified parent.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $parent = Parents::findOrFail($id);
            
            // Delete student links from pivot table first
            DB::table('parent_students')->where('id', $parent->id)->delete();
            
            // Delete parent
            $parent->delete();

            DB::commit();

            return redirect()->route('parents.index')
                ->with('success', 'Parent deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete parent: ' . $e->getMessage());
            return redirect()->route('parents.index')
                ->with('error', 'Failed to delete parent.');
        }
    }
}