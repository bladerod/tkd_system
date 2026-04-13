<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\BeltLevel;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardPopulateController extends Controller
{
    public function index()
    {   
        $students = DB::table('students')
            ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) as student_name"), 'student_code')
            ->where('status', 'active')
            ->get();
        $todayAttendance = AttendanceLog::with(['student', 'classSession.class', 'classSession.instructor'])
        ->whereDate('checkin_time', today())
        ->orderBy('checkin_time', 'desc')
        ->get();
        $parents = User::where('role', 'parent')->get();
        $beltlevels = BeltLevel::all();
        $branches = Branch::all();

        return view('dashboard', compact('branches', 'beltlevels', 'parents', 'students', 'todayAttendance'));
    }

    public function store(Request $request)
    {
        Log::info('Store called', $request->all());

        $validate = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'belt_level' => 'required',
            'status' => 'required',
            'medical_notes' => 'nullable|string',
            'allergies' => 'nullable|string',
            'contact_person' => 'required|string',
            'contact_number' => 'required|string',
            'primary_parent_id' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'branch_id' => $validate['branch_id'],
            'role' => 'student',
            'fname' => $validate['first_name'],
            'lname' => $validate['last_name'],
            'email' => $validate['email'],
            'username' => strtolower($validate['first_name']) . '.' . strtolower($validate['last_name']) . rand(100, 999),
            'password' => \Illuminate\Support\Facades\Hash::make($validate['password']),
            'status' => $validate['status'] === 'active' ? 'active' : 'inactive',
        ]);

        try {
            DB::table('students')->insert([
                'user_id' => $user->id,
                'branch_id' => $validate['branch_id'],
                'student_code' => 'TKD-' . strtoupper(\Illuminate\Support\Str::random(5)),
                'first_name' => $validate['first_name'],
                'last_name' => $validate['last_name'],
                'birthdate' => $validate['birthdate'],
                'gender' => $validate['gender'],
                'current_belt' => $validate['belt_level'],
                'medical_notes' => $validate['medical_notes'] ?? null,
                'allergies' => $validate['allergies'] ?? null,
                'emergency_contact_name' => $validate['contact_person'],
                'emergency_contact_mobile' => $validate['contact_number'],
                'primary_parent_id' => $validate['primary_parent_id'] ?? null,
                'join_date' => now(),
                'status' => $validate['status'],
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Student insert error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Student created but profile failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Student successfully added!');
    }
}