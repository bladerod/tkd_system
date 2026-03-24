<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        $instructors = Instructor::select(
            'id',
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
            'status'
        )->get();

        return view('instructor', compact('instructors', 'branches'));
    }

   public function store(Request $request)
{
    $request->validate([
        'branch_id' => 'required',
        'fname' => 'required|string|max:255',
        'lname' => 'required|string|max:255',
        'email' => 'required|email|unique:instructors,email|unique:users,email',
        'username' => 'required|string|unique:instructors,username',
        'contact' => 'nullable|string|max:20',
        'password' => 'required|string|min:6|confirmed',
        'rank_belt' => 'nullable|string',
        'certification_level' => 'nullable|string',
        'specialization' => 'nullable|string|max:255',
        'bio' => 'nullable|string',
        'status' => 'required|string',
        'photo' => 'nullable|image|max:2048'
    ]);

    $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('instructors', 'public') : null;

    // Create user account para sa Flutter login
    $user = User::create([
        'branch_id' => $request->branch_id,
        'fname' => $request->fname,
        'lname' => $request->lname,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'instructor',
        'mobile' => $request->contact,
        'username' => $request->username,
        
    ]);

    // Create instructor record
    Instructor::create([
        'user_id' => $user->id,
        'fname' => $request->fname,
        'lname' => $request->lname,
        'email' => $request->email,
        'username' => $request->username,
        'contact' => $request->contact,
        'password' => bcrypt($request->password),
        'rank_belt' => $request->rank_belt,
        'certification_level' => $request->certification_level,
        'specialization' => $request->specialization,
        'bio' => $request->bio,
        'status' => $request->status,
        'active_flag' => 1,
        'photo' => $photoPath
    ]);

    return back()->with('success', 'Instructor added successfully!');
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:instructors,email,'.$id,
            'username' => 'required|string|unique:instructors,username,'.$id,
            'contact' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'rank_belt' => 'nullable|string',
            'certification_level' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'status' => 'required|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        $instructor = Instructor::findOrFail($id);

        if ($request->hasFile('photo')) {
            $instructor->photo = $request->file('photo')->store('instructors', 'public');
        }

        $instructor->fname = $request->fname;
        $instructor->lname = $request->lname;
        $instructor->email = $request->email;
        $instructor->username = $request->username;
        $instructor->contact = $request->contact;
        $instructor->rank_belt = $request->rank_belt;
        $instructor->certification_level = $request->certification_level;
        $instructor->specialization = $request->specialization;
        $instructor->bio = $request->bio;
        $instructor->status = $request->status;

        if ($request->password) {
            $instructor->password = bcrypt($request->password);
        }

        $instructor->save();

        return back()->with('success', 'Instructor updated successfully!');
    }

    public function destroy($id)
    {
        $instructor = Instructor::findOrFail($id);
        $instructor->delete();

        return back()->with('success', 'Instructor deleted successfully!');
    }
}
