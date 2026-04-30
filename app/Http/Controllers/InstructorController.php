<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function sanitizeInput($value)
    {
        if(is_string($value)){
            $value = trim($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $value;
    }
    public function index(Request $request)
{
    $branches = Branch::all();

    $query = Instructor::with('user')
        ->select(
            'id',
            'user_id',
            'fname',
            'lname',
            DB::raw("CONCAT(fname, ' ', lname) as name"),
            'contact',
            'email',
            'username',
            'rank_belt',
            'certification_level',
            'specialization',
            'bio',
            'status',
            'created_at'
        )
        ->orderByDesc('id');

    // Status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Certification level filter
    if ($request->filled('verification')) {
        $query->where('certification_level', $request->verification);
    }

    // Quick search by name, email, or contact
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('fname', 'like', '%' . $request->search . '%')
              ->orWhere('lname', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%')
              ->orWhere('contact', 'like', '%' . $request->search . '%');
        });
    }

    // Date range filter
    if ($request->filled('date_from')) {
        $query->whereDate('hire_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('hire_date', '<=', $request->date_to);
    }

    $instructors = $query->get();

    return view('instructor', compact('instructors', 'branches'));
}

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'branch_id' => 'required',
                'fname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\'-]+$/'],
                'lname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\'-]+$/'],
                'email' => 'required|email|unique:instructors,email|unique:users,email',
                'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9_.]+$/', 'unique:instructors,username'],
                'contact' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
                'password' => 'required|string|min:6|confirmed',
                'rank_belt' => 'nullable|string',
                'certification_level' => 'nullable|string',
                'specialization' => ['nullable', 'string', 'max:255', 'regex:/^[^<>]+$/'],
                'bio' => ['nullable', 'string', 'regex:/^[^<>]+$/'],
                'status' => 'required|string',
                'photo' => 'nullable|image|max:2048'
            ]);

            // Silent cleanups using preg_replace and sanitizeInput
            $data = [
                'fname' => preg_replace('/[^a-zA-Z\s\'-]/', '', $this->sanitizeInput($request->fname)),
                'lname' => preg_replace('/[^a-zA-Z\s\'-]/', '', $this->sanitizeInput($request->lname)),
                'email' => filter_var($this->sanitizeInput($request->email), FILTER_SANITIZE_EMAIL),
                'username' => preg_replace('/[^a-zA-Z0-9_.]/', '', $this->sanitizeInput($request->username)),
                'contact' => preg_replace('/[^\d+]/', '', $this->sanitizeInput($request->contact)),
                'specialization' => $this->sanitizeInput($request->specialization),
                'bio' => $this->sanitizeInput($request->bio),
            ];

            $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('instructors', 'public') : null;

            // Create user account
            $user = User::create([
                'branch_id' => $request->branch_id,
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'email' => $data['email'],
                'password' => bcrypt($request->password),
                'role' => 'instructor',
                'mobile' => $data['contact'],
                'username' => $data['username'],
            ]);

            if (!$user) {
                throw new \Exception('Failed to create user account.');
            }

            // Create instructor record
            Instructor::create([
                'user_id' => $user->id,
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'email' => $data['email'],
                'username' => $data['username'],
                'contact' => $data['contact'],
                'password' => bcrypt($request->password),
                'rank_belt' => $request->rank_belt,
                'certification_level' => $request->certification_level,
                'specialization' => $data['specialization'],
                'bio' => $data['bio'],
                'status' => $request->status,
                'active_flag' => 1,
                'photo' => $photoPath
            ]);

            DB::commit();
            return back()->with('success', 'Instructor added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add instructor: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'branch_id' => 'required',
                'fname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\'-]+$/'],
                'lname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\'-]+$/'],
                'email' => 'required|email|unique:instructors,email,'.$id,
                'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9_.]+$/', 'unique:instructors,username,'.$id],
                'contact' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
                'password' => 'nullable|string|min:6|confirmed',
                'rank_belt' => 'nullable|string',
                'certification_level' => 'nullable|string',
                'specialization' => ['nullable', 'string', 'max:255', 'regex:/^[^<>]+$/'],
                'bio' => ['nullable', 'string', 'regex:/^[^<>]+$/'],
                'status' => 'required|string',
                'photo' => 'nullable|image|max:2048'
            ]);

            $data = [
                'fname' => preg_replace('/[^a-zA-Z\s\'-]/', '', $this->sanitizeInput($request->fname)),
                'lname' => preg_replace('/[^a-zA-Z\s\'-]/', '', $this->sanitizeInput($request->lname)),
                'email' => filter_var($this->sanitizeInput($request->email), FILTER_SANITIZE_EMAIL),
                'username' => preg_replace('/[^a-zA-Z0-9_.]/', '', $this->sanitizeInput($request->username)),
                'contact' => preg_replace('/[^\d+]/', '', $this->sanitizeInput($request->contact)),
                'specialization' => $this->sanitizeInput($request->specialization),
                'bio' => $this->sanitizeInput($request->bio),
            ];

            $instructor = Instructor::findOrFail($id);

            if ($request->hasFile('photo')) {
                $instructor->photo = $request->file('photo')->store('instructors', 'public');
            }

            User::where('id', $instructor->user_id)->update([
                'branch_id' => $request->branch_id,
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'email' => $data['email'],
                'role' => 'instructor',
                'mobile' => $data['contact'],
                'username' => $data['username'],
                'password' => $request->password ? bcrypt($request->password) : $instructor->password,
            ]);

            $instructor->fname = $data['fname'];
            $instructor->lname = $data['lname'];
            $instructor->email = $data['email'];
            $instructor->username = $data['username'];
            $instructor->contact = $data['contact'];
            $instructor->rank_belt = $request->rank_belt;
            $instructor->certification_level = $request->certification_level;
            $instructor->specialization = $data['specialization'];
            $instructor->bio = $data['bio'];
            $instructor->status = $request->status;

            if ($request->password) {
                $instructor->password = bcrypt($request->password);
            }

            $instructor->save();

            DB::commit();
            return back()->with('success', 'Instructor updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update instructor: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $instructor = Instructor::findOrFail($id);
        $instructor->delete();

        return back()->with('success', 'Instructor deleted successfully!');
    }
}
