<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AttendanceLog;
use App\Models\BeltLevel;
use App\Models\Branch;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardPopulateController extends Controller
{
    public function sanitizeInput($value)
    {
        if (is_string($value)) {
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
        $parents = DB::table('parents')
            ->join('users', 'parents.user_id', '=', 'users.id')
            ->select('parents.id as parent_id', 'users.fname', 'users.lname')
            ->get();
        $beltlevels = BeltLevel::all();
        $branches = Branch::all();
        $classes = Classes::all();

        $outstandingBalance = DB::table('invoices')
            ->whereIn('status', ['pending', 'overdue'])
            ->sum('total_due');


        // for revenue chart
        $revenueDailyLabels = [];
        $revenueDailyValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDay($i);
            $revenueDailyLabels[] = $date->format('M d');

            $dailyTotal = DB::table('payments')
                ->whereDate('paid_at', $date->toDateString())
                ->sum('amount');
            $revenueDailyValues[] = (float) $dailyTotal;
        }
        $revenueMonthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $revenueMonthlyValues = [];
        $currentYear = date('Y');

        for ($month = 1; $month <= 12; $month++) {
            $monthlyTotal = DB::table('payments')
                ->whereYear('paid_at', $currentYear)
                ->whereMonth('paid_at', $month)
                ->sum('amount');

            $revenueMonthlyValues[] = (float) $monthlyTotal;
        }

        $revenueChartData = [
            'daily' => [
                'labels' => $revenueDailyLabels,
                'values' => $revenueDailyValues
            ],
            'monthly' => [
                'labels' => $revenueMonthlyLabels,
                'values' => $revenueMonthlyValues
            ]
        ];
        // for revenue chart

        // for enrollee's chart
        $dailyLabels = [];
        $dailyValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyLabels[] = $date->format('M d');
            $dailyValues[] = DB::table('students')
                ->whereDate('created_at', $date->toDateString())
                ->count();
        }

        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyValues = [];
        $currentYear = date('Y');

        for ($month = 1; $month <= 12; $month++) {
            $monthlyValues[] = DB::table('students')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->count();
        }
        $enrolleesChartData = [
            'daily' => [
                'labels' => $dailyLabels,
                'values' => $dailyValues
            ],
            'monthly' => [
                'labels' => $monthlyLabels,
                'values' => $monthlyValues
            ]
        ];
        // for enrollee's chart


        // for attendance Heatmap chart
        $attendances = AttendanceLog::where('status', 1)
            ->where('checkin_time', '>=', now()->subDays(30))
            ->get(['checkin_time']);

        $timeBuckets = [8, 10, 12, 14, 16, 18, 20, 22];
        $daysOfWeek = [1, 2, 3, 4, 5, 6, 7];

        $heatmapData = [];
        foreach ($timeBuckets as $time) {
            foreach ($daysOfWeek as $day) {
                $heatmapData[$time][$day] = 0;
            }
        }

        $maxHeatmapCount = 0;

        foreach ($attendances as $attendance) {
            $date = \Carbon\Carbon::parse($attendance->checkin_time);
            $day = $date->dayOfWeekIso; // 1 (Mon) through 7 (Sun)
            $hour = $date->hour;

            $bucket = null;
            if ($hour >= 7 && $hour < 9)
                $bucket = 8;
            elseif ($hour >= 9 && $hour < 11)
                $bucket = 10;
            elseif ($hour >= 11 && $hour < 13)
                $bucket = 12;
            elseif ($hour >= 13 && $hour < 15)
                $bucket = 14;
            elseif ($hour >= 15 && $hour < 17)
                $bucket = 16;
            elseif ($hour >= 17 && $hour < 19)
                $bucket = 18;
            elseif ($hour >= 19 && $hour < 21)
                $bucket = 20;
            elseif ($hour >= 21 || $hour < 7)
                $bucket = 22; // Groups late night classes

            if ($bucket !== null && isset($heatmapData[$bucket][$day])) {
                $heatmapData[$bucket][$day]++;
                if ($heatmapData[$bucket][$day] > $maxHeatmapCount) {
                    $maxHeatmapCount = $heatmapData[$bucket][$day];
                }
            }
        }

        if ($maxHeatmapCount == 0)
            $maxHeatmapCount = 1;

        $timeLabels = [
            8 => '8 AM',
            10 => '10 AM',
            12 => '12 PM',
            14 => '2 PM',
            16 => '4 PM',
            18 => '6 PM',
            20 => '8 PM',
            22 => '10 PM'
        ];
        // for attendance Heatmap chart

        return view('dashboard', compact(
            'branches',
            'beltlevels',
            'parents',
            'students',
            'todayAttendance',
            'enrolleesChartData',
            'heatmapData',
            'timeLabels',
            'maxHeatmapCount',
            'revenueChartData',
            'outstandingBalance',
            'classes'
        ));
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

        $photoPath = null;
        if ($request->hasFile('photo_url')) {
            $photoPath = $request->file('photo_url')->store('student-photos', 'public');
        }

        $user = User::create([
            'branch_id' => $validate['branch_id'],
            'role' => 'student',
            'fname' => $data['first_name'],
            'lname' => $data['last_name'],
            'email' => $data['email'],
            'username' => strtolower(str_replace(' ', '', $data['first_name'])) . '.' . strtolower(str_replace(' ', '', $data['last_name'])) . rand(100, 999),
            'password' => \Illuminate\Support\Facades\Hash::make($validate['password']),
            'status' => $validate['status'] === 'active' ? 'active' : 'inactive',
            'photo_url' => $photoPath,
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
                'photo_url' => $photoPath,
                'created_at' => now(),
            ]);

            $newStudent = DB::table('students')->where('user_id', $user->id)->first();

            if ($newStudent && !empty($validate['primary_parent_id'])) {
                DB::table('parent_students')->insert([
                    'parent_id' => $validate['primary_parent_id'],
                    'student_id' => $newStudent->id,
                    'created_at' => now(),
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Student insert error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Student created but profile failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Student successfully added!');
    }
    public function announcement(Request $request)
    {
        $validate = $request->validate([
            'title' => ['required', 'string', 'max:200', 'regex:/^[a-zA-Z0-9\s\-.,!?\'"]+$/'],
            'message' => ['required', 'string', 'regex:/^[^<>]+$/'],
            'target_type' => 'required|in:all,class,belt,branch',
            'class_id' => 'nullable|exists:classes,id',
            'belt_level' => 'nullable|string',
            'branch_id' => 'nullable|exists:branches,id',
            'channel' => 'required|array',
            'channel.*' => 'in:App,SMS,Email',
            'expire_date' => 'required|date|after_or_equal:today',
            'created_by_user_id' => 'required|exists:users,id',
        ]);

        // Additional conditional validation
        if ($request->target_type === 'class' && !$request->filled('class_id')) {
            return redirect()->back()
                ->with('error', 'Please select a class when targeting by Class.')
                ->withInput();
        }

        if ($request->target_type === 'branch' && !$request->filled('branch_id')) {
            return redirect()->back()
                ->with('error', 'Please select a branch when targeting by Branch.')
                ->withInput();
        }

        if ($request->target_type === 'belt' && !$request->filled('belt_level')) {
            return redirect()->back()
                ->with('error', 'Please select a belt level when targeting by Belt.')
                ->withInput();
        }
        // Convert channel array to comma-separated string
        $channelString = implode(',', $request->channel);

        $data = [
            'created_by_user_id' => $request->created_by_user_id,
            'target_type' => $request->target_type,
            'title' => $this->sanitizeInput($request->title),
            'message' => $this->sanitizeInput($request->message),
            'channel' => $channelString,
            'publish_date' => Carbon::now(),
            'expire_date' => $request->expire_date,
        ];

        // Add conditional fields based on target type
        if ($request->target_type === 'class') {
            $data['class_id'] = $request->class_id;
            $data['belt_level'] = null;
            $data['branch_id'] = null;
        } elseif ($request->target_type === 'belt') {
            $data['belt_level'] = $request->belt_level;
            $data['class_id'] = null;
            $data['branch_id'] = null;
        } elseif ($request->target_type === 'branch') {
            $data['branch_id'] = $request->branch_id;
            $data['class_id'] = null;
            $data['belt_level'] = null;
        } else { // all
            $data['class_id'] = null;
            $data['belt_level'] = null;
            $data['branch_id'] = null;
        }


        $announcement = Announcement::create($data);
        return redirect()->back()->with('success', 'The message has been successfully created.');
    }
}