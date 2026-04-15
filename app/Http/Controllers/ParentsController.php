<?php

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
        $parents = Parents::with(['parent.user', 'students'])->get();

        $students = Student::select('id', 'student_name', 'student_code')
                        ->where('status', 'active')
                        ->get();

        $users = User::where('role', 'parent')
                    ->orWhereDoesntHave('parent')
                    ->get();

        $branches = Branch::all();

        return view('parent', compact(
            'parents',
            'students',
            'users',
            'branches'
        ));
    }

    /**
     * Store a newly created parent.
     */
    public function store(Request $request)
    {
        try {

            // Validate the request
            $request->validate([
                'fname' => 'required|string|max:170',
                'lname' => 'required|string|max:170',
                'address' => 'required|string|max:255',
                'relationship_note' => 'nullable|string',
                'mobile' => 'nullable|string|max:13',
                'status' => 'required|in:1,0',
                'email' => 'nullable|email|unique:users,email',
                'password' => 'nullable|string|min:6|confirmed',
                'students' => 'nullable|array',
                'students.*' => 'exists:students,id',
                'photo_url' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            // Check if email exists
            $existingUser = null;
            $user = null;

            if ($request->filled('email')) {
                $existingUser = User::where('email', $request->email)->first();

                if ($existingUser) {
                    // If user exists, use that user account
                    $user = $existingUser;

                    // Update user details if needed
                    $user->update([
                        'fname' => $request->fname,
                        'lname' => $request->lname,
                        'mobile' => $request->mobile ?? $user->mobile,
                        'status' => $request->status,
                    ]);
                }
            }

            if (!$user) {

                if ($request->filled('email') && User::where('email', $request->email)->exists()) {
                    throw new \Exception('The email address is already registered. Please use a different email or login to an existing account.');
                }

                $user = User::create([
                    'branch_id' => 1, // Default branch
                    'role' => 'parent',
                    'fname' => $request->fname,
                    'lname' => $request->lname,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'password' => Hash::make($request->password ?? 'default123'),
                    'status' => $request->status,
                    'created_at' => now(),
                ]);
            }

            $existingParent = Parents::where('user_id', $user->id)->first();

            if ($existingParent) {
                throw new \Exception('A parent record already exists for this user. Please edit the existing record instead.');
            }

            // Handle file upload for ID/photo
            $photoUrl = null;
            if ($request->hasFile('photo_url')) {
                $photoUrl = $request->file('photo_url')->store('parent-ids', 'public');
            }

            // Create parent record
            $parent = Parents::create([
                'user_id' => $user->id,
                'emergency_contact' => $request->mobile,
                'relationship_note' => $request->relationship_note,
                'address' => $request->address,
                'id_verified_flag' => $photoUrl ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Link students if any using the parent_students table
            if ($request->has('students') && !empty($request->students)) {
                foreach ($request->students as $studentId) {
                    DB::table('parent_students')->insert([
                        'parent_id' => $parent->id,
                        'student_id' => $studentId,
                        'relationship' => $request->relationship_note ?? 'guardian',
                        'is_primary' => 0,
                        'created_at' => now(),
                    ]);
                }
            }

            DB::commit();

            $message = $existingUser
                ? 'Parent created successfully! Linked to existing user account.'
                : 'Parent created successfully! New user account has been created.';

            return redirect()->route('dashboard.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create parent: ' . $e->getMessage());
            Log::error('Request data: ' . json_encode($request->all()));

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
        $parent = User::with('students')
            ->where('role', 'parent')
            ->where('id', $id) // ✅ FIX HERE
            ->firstOrFail();

        return response()->json([
            'id' => $parent->id,
            'full_name' => $parent->fname . ' ' . $parent->lname,
            'email' => $parent->email,
            'mobile' => $parent->mobile,
            'students' => $parent->students,
            'children_count' => $parent->students->count(),
        ]);

    } catch (\Exception $e) {
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
