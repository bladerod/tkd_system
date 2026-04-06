<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{


    /**
     * Sanitize input strings to prevent XSS attacks
     */
    private function sanitizeInput($value)
    {
        if (is_string($value)) {
            // Trim whitespace and strip HTML tags
            $value = trim($value);
            $value = strip_tags($value);
            // Convert special characters to HTML entities
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $value;
    }

    /**
     * Sanitize array of inputs recursively
     */
    private function sanitizeArray(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = $this->sanitizeInput($value);
            } elseif (is_array($value)) {
                $data[$key] = $this->sanitizeArray($value);
            }
        }
        return $data;
    }

    /**
     * Clean and prepare input data
     */
    private function prepareInputData(Request $request, array $fields)
    {
        $data = [];
        foreach ($fields as $field) {
            if ($request->has($field) && $request->$field !== null) {
                // Special handling for different field types
                switch ($field) {
                    case 'email':
                        $data[$field] = filter_var(trim($request->$field), FILTER_SANITIZE_EMAIL);
                        $data[$field] = filter_var($data[$field], FILTER_VALIDATE_EMAIL) ? $data[$field] : '';
                        break;
                    
                    case 'mobile': 
                        // Remove all non-numeric characters except + for mobile numbers
                        $data[$field] = preg_replace('/[^0-9+]/', '', trim($request->$field));
                        break;
                        
                    case 'fname':
                    case 'lname':
                        // Allow letters, spaces, hyphens, and apostrophes for names
                        $data[$field] = preg_replace('/[^a-zA-Z\s\'-]/', '', trim($request->$field));
                        $data[$field] = $this->sanitizeInput($data[$field]);
                        break;
                    
                    default:
                        $data[$field] = $this->sanitizeInput($request->$field);
                }
            }
        }
        return $data;
    }

    /**
     * Display a listing of users.
     */
    public function index()
    {
        // Get all users with their branches
        $users = User::with('branch')->get();
        $users = User::where('role','admin')
        ->where('status',1)
        ->get();
        // Get all branches for the dropdown
        $branches = Branch::all();
        
        // Return the view with data
        return view('user', [
            'users' => $users,
            'branches' => $branches
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        // Prepare and sanitize input data first
        $sanitizedData = $this->prepareInputData($request, [
            'branch_id', 'role', 'fname', 'lname', 'email', 'mobile' 
        ]);

        // Merge sanitized data back to request
        $request->merge($sanitizedData);

        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'role' => 'required|in:admin,staff',
            'fname' => ['required', 'string', 'min:2', 'max:50', "regex:/^[a-zA-Z\s\'-]+$/"],
            'lname' => ['required', 'string', 'min:2', 'max:50', "regex:/^[a-zA-Z\s\'-]+$/"],
            'email' => 'required|email|max:100|unique:users,email',
            'mobile' => ['required', 'string', 'max:13', 'regex:/^(09|\+639)\d{9}$/'],
            'password' => 'required|string|min:6|max:100',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo_url')) {
            // Validate file name to prevent path traversal
            $file = $request->file('photo_url');
            $originalName = $file->getClientOriginalName();
            
            // Generate a safe, unique filename
            $safeFilename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Store with safe filename
            $photoPath = $file->storeAs('profile-photos', $safeFilename, 'public');
            
            // Log the upload for audit trail
            Log::info('Profile photo uploaded', [
                'user' => $request->email,
                'original_name' => $originalName,
                'saved_as' => $safeFilename
            ]);
        }

        // Additional sanitization for fields that might have been missed
        $fname = $this->sanitizeInput($request->fname);
        $lname = $this->sanitizeInput($request->lname);
        $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
        
        // Create user with sanitized data
        User::create([
            'branch_id' => (int) $request->branch_id,
            'role' => $this->sanitizeInput($request->role),
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'mobile' => $this->sanitizeInput($request->mobile),
            'password' => Hash::make($request->password),
            'photo_url' => $photoPath,
        ]);

        Log::info('User created successfully', [
            'email' => $email,
            'role' => $request->role
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        // Cast ID to integer to prevent SQL injection
        $id = (int) $id;
        
        $user = User::with('branch')->findOrFail($id);
        
        // Sanitize output data before sending as JSON
        $userData = [
            'id' => $user->id,  // Make sure this is 'id', not 'user_id'
            'branch_id' => $user->branch_id,
            'role' => $this->sanitizeInput($user->role),
            'fname' => $this->sanitizeInput($user->fname),
            'lname' => $this->sanitizeInput($user->lname),
            'email' => $this->sanitizeInput($user->email),
            'mobile' => $this->sanitizeInput($user->mobile),
            'status' => $user->status,
            'photo_url' => $user->photo_url ? asset('storage/' . $user->photo_url) : null,
            'name' => $this->sanitizeInput($user->fname . ' ' . $user->lname)
        ];
        
        return response()->json($userData);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        // Cast ID to integer
        $id = (int) $id;
        
        $user = User::findOrFail($id);

        // Prepare and sanitize input data
        $sanitizedData = $this->prepareInputData($request, [
            'branch_id', 'role', 'fname', 'lname', 'email', 'mobile'
        ]);

        // Merge sanitized data back to request
        $request->merge($sanitizedData);

        // Validate with custom messages
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'role' => 'required|in:admin,staff',
            'fname' => ['required', 'string', 'min:2', 'max:50', "regex:/^[a-zA-Z\s\'-]+$/"],
            'lname' => ['required', 'string', 'min:2', 'max:50', "regex:/^[a-zA-Z\s\'-]+$/"],
            'email' => 'required|email|max:100|unique:users,email,' . $id, 
            'mobile' => ['required', 'string', 'max:13', 'regex:/^(09|\+639)\d{9}$/'],
            'password' => 'nullable|string|min:6', 
            'status' => 'required|in:0,1',
        ]);

        // Handle photo upload
        $photoPath = $user->photo_url;
        if ($request->hasFile('photo_url')) {
            // Validate file name to prevent path traversal
            $file = $request->file('photo_url');
            $originalName = $file->getClientOriginalName();
            
            // Generate a safe, unique filename
            $safeFilename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Delete old photo if exists
            if ($user->photo_url && Storage::disk('public')->exists($user->photo_url)) {
                Storage::disk('public')->delete($user->photo_url);
                Log::info('Old profile photo deleted', ['user' => $user->email]);
            }
            
            // Store with safe filename
            $photoPath = $file->storeAs('profile-photos', $safeFilename, 'public');
            
            Log::info('Profile photo updated', [
                'user' => $user->email,
                'original_name' => $originalName,
                'saved_as' => $safeFilename
            ]);
        }

        // Sanitize all fields before update
        $userData = [
            'branch_id' => (int) $request->branch_id,
            'role' => $this->sanitizeInput($request->role),
            'fname' => $this->sanitizeInput($request->fname),
            'lname' => $this->sanitizeInput($request->lname),
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'mobile' => $this->sanitizeInput($request->mobile), 
            'photo_url' => $photoPath,
            'status' => (int) $request->status,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
            Log::info('Password updated for user', ['user' => $user->email]);
        }

        $user->update($userData);

        Log::info('User updated successfully', [
            'id' => $id,
            'email' => $user->email,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        try {
            $id = (int) $id;
            $user = User::findOrFail($id);
            
            // Handle Profile Photo Deletion
            if ($user->photo_url && Storage::disk('public')->exists($user->photo_url)) {
                Storage::disk('public')->delete($user->photo_url);
            }

            // Truly delete the record from the database
            $user->delete(); 

            Log::info('User permanently deleted', ['id' => $id, 'email' => $user->email, 'fname' => $user->fname, 'lname'=> $user->lname]);
            
            return redirect()->route('users.index')->with('success', 'User deleted successfully!');
            
        } catch (\Exception $e) {
            Log::error('Failed to delete user', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('users.index')->with('error', 'Failed to delete user.');
        }
    }
}