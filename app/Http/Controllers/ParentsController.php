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

    public function sanitizeInput($value)
    {
        if(is_string($value))
        {
            $value = trim($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $value;
    }
    /**
     * Display a listing of parents.
     */
public function index()
{
    $parents = Parents::with(['user', 'students'])->get();

    $parentList = $parents->map(function ($p) {

        // ✅ avoid null crash if user missing
        $user = $p->user;

        return [

            'id' => $user->id, // <- this is what your modal should use
            'user_id' => $user->id,

            'name' => trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')),
            'email' => $user->email ?? '',
            'mobile' => $user->mobile ?? '',

            'children_count' => $p->students ? $p->students->count() : 0,

            'total_balance' => 0,

            'status' => $user->status ?? 0,
            'id_verified_flag' => $p->id_verified_flag ?? 0
        ];
    });

    return view('parent', compact('parentList'));
}

    /**
     * Store a newly created parent.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $request->validate([
                // Added strict regex to prevent bad characters from slipping through
                'fname' => ['required', 'string', 'max:170', 'regex:/^[a-zA-Z\s\'-]+$/'],
                'lname' => ['required', 'string', 'max:170', 'regex:/^[a-zA-Z\s\'-]+$/'],
                'address' => 'required|string|max:255',
                'relationship_note' => ['nullable', 'string', 'regex:/^[^<>]+$/'],
                'mobile' => ['nullable', 'string', 'max:13', 'regex:/^[\d\s\-\+\(\)]+$/'],
                'status' => 'required|in:1,0',
                'email' => 'nullable|email|unique:users,email',
                'password' => 'nullable|string|min:6|confirmed',
                'students' => 'nullable|array',
                'students.*' => 'exists:students,id',
                'photo_url' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            $data = [
                'fname' => preg_replace('/[^a-zA-Z\s\'-]/', '', $this->sanitizeInput($request->fname)),
                'lname' => preg_replace('/[^a-zA-Z\s\'-]/', '', $this->sanitizeInput($request->lname)),
                'address' => $this->sanitizeInput($request->address),
                'relationship_note' => $this->sanitizeInput($request->relationship_note),
                'mobile' => preg_replace('/[^\d+]/', '', $this->sanitizeInput($request->mobile)),
                'email' => filter_var($this->sanitizeInput($request->email), FILTER_SANITIZE_EMAIL)
            ];

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
                    'fname' => $data['fname'],
                    'lname' => $data['lname'],
                    'email' => $data['email'],
                    'mobile' => $data['mobile'],
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
                        'relationship' => $data['relationship_note'] ?? 'guardian',
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
    $parent = Parents::with(['user', 'students'])
        ->where('user_id', $id)
        ->first();

    if (!$parent) {
        return response()->json(['error' => 'Not found'], 404);
    }

    return response()->json([
        'id' => $parent->id,
        'full_name' => $parent->user->fname . ' ' . $parent->user->lname,
        'email' => $parent->user->email,
        'mobile' => $parent->user->mobile,
        'students' => $parent->students,
        'relationship_note' => $parent->relationship_note,
    ]);
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

    public function getChildrenDetails($id)
{
    $parent = Parents::with('students')
        ->where('user_id', $id)
        ->first();

    return response()->json([
        'students' => $parent ? $parent->students : []
    ]);
}

// =========================
// BILLING
// =========================
public function billing($id)
{
    return DB::table('invoices')
        ->join('students', 'students.id', '=', 'invoices.student_id')
        ->where('students.primary_parent_id', $id)
        ->select('invoices.*', 'students.first_name as student_name')
        ->get();
}

// =========================
// PAYMENTS
// =========================
public function payments($id)
{
    return DB::table('paymentsview')
        ->where('parent_id', $id)
        ->get();
}

// =========================
// CHAT
// =========================
public function chat($id)
{
    return DB::table('chat_messages')
        ->where('sender_user_id', $id)
        ->get();
}

// =========================
// ACTIVITY
// =========================
public function activity($id)
{
    return DB::table('activity_logs')
        ->where('parent_id', $id)
        ->get();
}

// =========================
// NOTIFICATIONS
// =========================
public function notifications($id)
{
    return DB::table('notifications')
        ->where('user_id', $id)
        ->get();
}
}
