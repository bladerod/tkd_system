<?php

namespace App\Http\Controllers;

use App\Models\BeltLevel;
use App\Models\Branch;
use App\Models\Student;
use App\Models\StudentDisplay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardPopulateController extends Controller
{
    public function index()
    {   

        $students = StudentDisplay::select('id', 'student_name', 'student_code')
                    ->where('status', 'active')
                    ->get();
        $parents = User::where('role', 'parent')->get();
        $beltlevels = BeltLevel::all();
        $branches = Branch::all();

        return view('dashboard', compact('branches', 'beltlevels', 'parents', 'students'));

    }


    public function store(Request $request)
    {
        $validate = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'email' => 'required', 
            'password' => 'required', 
            'belt_level' => 'required',
            'status' => 'required',
            'medical_notes' => 'nullable|string',
            'allergies' => 'nullable|string',
            'contact_person' => 'required|string',
            'contact_number' => 'required|string',
            'primary_parent_id' => 'required|exists:users,id',
        ]);

        Student::create([
            'branch_id' => $validate['branch_id'],
            'first_name' => $validate['first_name'],
            'last_name' => $validate['last_name'],
            'birthdate' => $validate['birthdate'],
            'gender' => $validate['gender'],
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'current_belt' => $validate['belt_level'],
            'medical_notes' => $validate['medical_notes'],
            'allergies' => $validate['allergies'],
            'emergency_contact_name' => $validate['contact_person'],
            'emergency_contact_mobile' => $validate['contact_number'],
            'primary_parent_id' => $validate['primary_parent_id'],
            'join_date' => now(),
            'status' => $validate['status'],
        ]);

        return redirect()->route('dashboard.index')->with('success', 'Student added successfully!');
    }
}
