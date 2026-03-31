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
            'email' => 'required|email|unique:users,email', 
            'password' => 'required|min:6',
            'belt_level' => 'required',
            'status' => 'required',
            'medical_notes' => 'nullable|string',
            'allergies' => 'nullable|string',
            'contact_person' => 'required|string',
            'contact_number' => 'required|string',
            'primary_parent_id' => 'required|exists:users,id',
        ]);

        $user = User::create([
            'branch_id' => $validate['branch_id'],
            'role' => 'student',
            'username' => strtolower($validate['first_name'] . '.' . $validate['last_name']) . rand(100, 999), // <-- Added username
            'fname' => $validate['first_name'],
            'lname' => $validate['last_name'],
            'email' => $validate['email'],
            'mobile' => $validate['contact_number'], 
            'password' => Hash::make($validate['password']),
            'status' => $validate['status'] === 'active' ? 1 : 0,
        ]);

        $currentYear = date('y');

        $lastStudent = Student::where('student_code', 'LIKE', $currentYear . '-%')
                              ->orderBy('id', 'desc')
                              ->first();

        if ($lastStudent && $lastStudent->student_code) {
            $lastSequence = (int) explode('-', $lastStudent->student_code)[1];
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        $paddedSequence = str_pad($newSequence, 5, '0', STR_PAD_LEFT);

        $studentCode = $currentYear . '-' . $paddedSequence;

        Student::create([
            'user_id' => $user->id,
            'branch_id' => $validate['branch_id'],
            'student_code' => $studentCode, // <-- Added student code
            'first_name' => $validate['first_name'],
            'last_name' => $validate['last_name'],
            'middle_name' => $validate['middle_name'] ?? null,
            'birthdate' => $validate['birthdate'],
            'gender' => $validate['gender'],
            'current_belt' => $validate['belt_level'],
            'medical_notes' => $validate['medical_notes'],
            'allergies' => $validate['allergies'],
            'emergency_contact_name' => $validate['contact_person'],
            'emergency_contact_mobile' => $validate['contact_number'],
            'primary_parent_id' => $validate['primary_parent_id'],
            'join_date' => now(),
            'status' => $validate['status'],
        ]);

        return redirect()->back()->with('success', 'Student successfully added!');
    }
}