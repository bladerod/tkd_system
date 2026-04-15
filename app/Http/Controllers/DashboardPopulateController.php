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
    public function sanitizeInput($value)
    {
        if(is_string($value)){
            $value = trim($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $value;
    }
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
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\'-]+$/'], 
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\'-]+$/'],
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'belt_level' => 'required',
            'status' => 'required',
            'medical_notes' => ['nullable', 'string', 'regex:/^[^<>]+$/'],
            'allergies' => ['nullable', 'string', 'regex:/^[^<>]+$/'], 
            'contact_person' => ['required', 'string', 'regex:/^[a-zA-Z0-9\s\-.,!?\'"]+$/'],
            'contact_number' => ['required', 'string', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'primary_parent_id' => 'nullable',
            'email' => ['required', 'email', 'unique:users,email'], 
            'password' => 'required|min:6',
        ]);

        $data = [
            'first_name' => preg_replace('/[^a-zA-Z\s\'-]/', '', $this->sanitizeInput($request->first_name)),
            'last_name' => preg_replace('/[^a-zA-Z\s\'-]/', '', $this->sanitizeInput($request->last_name)),
            'contact_person' => preg_replace('/[^a-zA-Z\s\'-]/', '', $this->sanitizeInput($request->contact_person)),
            'medical_notes' => $this->sanitizeInput($request->medical_notes),
            'allergies' => $this->sanitizeInput($request->allergies),
            'contact_number' => preg_replace('/[^\d+]/', '', $this->sanitizeInput($request->contact_number)),
            'email' => filter_var($this->sanitizeInput($request->email), FILTER_SANITIZE_EMAIL)
        ];

        $user = User::create([
            'branch_id' => $validate['branch_id'],
            'role' => 'student',
            'fname' => $data['first_name'],
            'lname' => $data['last_name'],
            'email' => $data['email'],
            'username' => strtolower(str_replace(' ', '', $data['first_name'])) . '.' . strtolower(str_replace(' ', '', $data['last_name'])) . rand(100, 999),
            'password' => \Illuminate\Support\Facades\Hash::make($validate['password']),
            'status' => $validate['status'] === 'active' ? 'active' : 'inactive',
        ]);

        $currentYear = date('y'); 
        
        $latestStudent = DB::table('students')
            ->where('student_code', 'LIKE', $currentYear . '-%')
            ->orderBy('student_code', 'desc')
            ->first();
            
        if ($latestStudent) {
            $lastSequence = (int) substr($latestStudent->student_code, 3);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }
        
        $newStudentCode = sprintf("%s-%05d", $currentYear, $nextSequence);

        try {
            DB::table('students')->insert([
                'user_id' => $user->id,
                'branch_id' => $validate['branch_id'],
                'student_code' => $newStudentCode,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'birthdate' => $validate['birthdate'], // Use validated date
                'gender' => $validate['gender'],
                'current_belt' => $validate['belt_level'],
                'medical_notes' => $data['medical_notes'] ?? null,
                'allergies' => $data['allergies'] ?? null,
                'emergency_contact_name' => $data['contact_person'],
                'emergency_contact_mobile' => $data['contact_number'],
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