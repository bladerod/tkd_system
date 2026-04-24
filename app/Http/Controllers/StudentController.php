<?php

namespace App\Http\Controllers;
use App\Models\AttendanceLog;
use App\Models\BeltLevel;
use App\Models\Certificate;
use App\Models\Classes;
use App\Models\CompetitionEntry;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class StudentController extends Controller
{
    public function index()
    {

        $beltlevels = BeltLevel::all();
        $classes = Classes::where('status', 'active')->get();
        $instructors = Instructor::where('active_flag', true)->with('user')->get();

        $students = Student::with(['primaryParent.user', 'classes.class', 'invoices', 'attendanceLogs'])
            ->withCount(['attendanceLogs as present_count' => function($q) {
                $q->whereIn('status', ['present', 'late']);
            }])
            ->orderBy('student_name', 'asc')
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'student_code' => $student->student_code,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'belt' => $student->current_belt ?? 'white',
                    'status' => $student->status ?? 'active',
                    'photo' => $student->photo_url,
                    'parent_name' => $student->primaryParent?->user?->name ?? 'N/A',
                    // 'balance' => $this->calculateBalance($student),
                    // 'attendance_rate' => $this->calculateAttendanceRate($student),
                    'class_id' => $student->classes->first()?->id,
                    'instructor_id' => $student->classes->first()?->primary_instructor_id,
                ];
            });

        return view('student', compact('students', 'beltlevels', 'classes', 'instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'gender' => 'required|in:male,female,other',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'current_belt' => 'required',
            'birthdate' => 'required|date',
            'primary_parent_id' => 'required|exists:users,id',
            'status' => 'required|in:active,inactive,suspended',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'medical_notes' => 'nullable|string',
            'allergies' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'contact_number' => 'nullable|string',
        ]);

        $studentCode = 'TKD-' . strtoupper(Str::random(5));

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('student-photos', 'public');
        }

        // First, create the user record for authentication
        $user = User::create([
            'branch_id' => $request->branch_id,
            'role' => 'student',
            'username' => strtolower($request->first_name . '.' . $request->last_name) . rand(100, 999),
            'fname' => $request->first_name,
            'lname' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->contact_number,
            'password' => Hash::make($request->password),
            'status' => $request->status === 'active' ? 1 : 0,
        ]);

        // Then create the student record
        Student::create([
            'user_id' => $user->id,
            'branch_id' => $request->branch_id,
            'student_code' => $studentCode,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'gender' => $request->gender,
            'current_belt' => $request->current_belt,
            'birthdate' => $request->birthdate,
            'join_date' => now(),
            'status' => $request->status,
            'primary_parent_id' => $request->primary_parent_id,
            'photo_url' => $photoPath,
            'medical_notes' => $request->medical_notes,
            'allergies' => $request->allergies,
            'emergency_contact_name' => $request->contact_person,
            'emergency_contact_mobile' => $request->contact_number,
        ]);

        return redirect()->route('dashboard')->with('success', 'Student added successfully!');
    }


    // public function getProfile($id)
    // {
    //     $total = AttendanceLog::where('student_id', $student->id)->count();
    //     $present = AttendanceLog::where('student_id', $student->id)
    //         ->where('status', 'present')
    //         ->count();

    //     return $total > 0 ? round(($present / $total) * 100, 1) : 0;
    // }

    // API methods for the modal tabs
    public function profile($studentId)
    {
        $student = Student::with(['parents.user', 'classes.instructor.user', 'subscriptions.plan'])
            ->findOrFail($studentId);

        return response()->json([
            'profile' => [
                'student_code' => $student->student_code,
                'name' => $student->first_name . ' ' . $student->last_name,
                'age' => $student->birthdate ? now()->diffInYears($student->birthdate) : null,
                'gender' => $student->gender,
                'birthdate' => $student->birthdate?->format('M d, Y'),
                'current_belt' => $student->current_belt,
                'join_date' => $student->join_date?->format('M d, Y'),
                'status' => $student->status,
                'emergency_contact_name' => $student->emergency_contact_name,
                'emergency_contact_mobile' => $student->emergency_contact_mobile,
            ],
            'parents' => $student->parents->map(function($parent) {
                return [
                    'name' => $parent->user->name,
                    'relationship' => $parent->pivot->relationship ?? 'guardian',
                    'is_primary' => $parent->pivot->is_primary ?? false,
                    'mobile' => $parent->user->mobile,
                    'email' => $parent->user->email,
                ];
            }),
            'classes' => $student->classes->map(function($class) {
                return [
                    'name' => $class->class_name,
                    'level' => $class->level,
                    'instructor' => $class->instructor?->user?->name ?? 'TBA',
                    'schedule' => $class->schedules->pluck('day_of_week')->toArray(),
                ];
            }),
            'subscription' => $student->subscriptions->first() ? [
                'plan' => $student->subscriptions->first()->plan->plan_name,
                'status' => $student->subscriptions->first()->status,
                'start' => $student->subscriptions->first()->start_date?->format('M d, Y'),
                'end' => $student->subscriptions->first()->end_date?->format('M d, Y'),
            ] : null,
        ]);
    }

    public function attendance($studentId)
    {
        $logs = AttendanceLog::with('classSession.class')
            ->where('student_id', $studentId)
            ->orderBy('checkin_time', 'desc')
            ->paginate(10);

        $stats = [
            'total_sessions' => AttendanceLog::where('student_id', $studentId)->count(),
            'present' => AttendanceLog::where('student_id', $studentId)->where('attendance_status', 'present')->count(),
            'late' => AttendanceLog::where('student_id', $studentId)->where('attendance_status', 'late')->count(),
            'absent' => AttendanceLog::where('student_id', $studentId)->where('attendance_status', 'absent')->count(),
            'excused' => AttendanceLog::where('student_id', $studentId)->where('attendance_status', 'excused')->count(),
            'by_method' => [
                'face' => AttendanceLog::where('student_id', $studentId)->where('method', 'face')->count(),
                'qr' => AttendanceLog::where('student_id', $studentId)->where('method', 'qr')->count(),
                'manual' => AttendanceLog::where('student_id', $studentId)->where('method', 'manual')->count(),
            ],
        ];

        return response()->json([
            'stats' => $stats,
            'logs' => $logs,
        ]);
    }
public function billing($id)
{
    $billings = DB::table('invoices') // change if needed
        ->where('student_id', $id)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($billings);
}
public function competition($id)
{
    $competitions = DB::table('competitions') // change table name
        ->where('id', $id)
        ->orderBy('date', 'desc')
        ->get();

    return response()->json($competitions);
}
public function certificates($id)
{
    $certificates = DB::table('certificates') // change if needed
        ->where('student_id', $id)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($certificates);
}

    // public function billing($studentId)
    // {
    //     $invoices = Invoice::with('payments')
    //         ->where('student_id', $studentId)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $summary = [
    //         'total_paid' => $invoices->flatMap->payments->sum('amount'),
    //         'total_pending' => $invoices->where('status', 'pending')->sum('total_due'),
    //         'total_overdue' => $invoices->where('status', 'overdue')->sum('total_due'),
    //         'lifetime_total' => $invoices->sum('total_due'),
    //     ];

    //     return response()->json([
    //         'summary' => $summary,
    //         'invoices' => $invoices->map(function($inv) {
    //             return [
    //                 'invoice_no' => $inv->invoice_no,
    //                 'status' => $inv->status,
    //                 'period' => $inv->billing_period_start?->format('M Y') . ' - ' . $inv->billing_period_end?->format('M Y'),
    //                 'due_date' => $inv->due_date?->format('M d, Y'),
    //                 'total_due' => $inv->total_due,
    //                 'payments' => $inv->payments->map(function($p) {
    //                     return [
    //                         'amount' => $p->amount,
    //                         'method' => $p->payment_method,
    //                         'date' => $p->paid_at,
    //                     ];
    //                 }),
    //             ];
    //         }),
    //         'current_subscription' => StudentSubscription::with('plan')
    //             ->where('student_id', $studentId)
    //             ->where('status', 'active')
    //             ->first()?->plan ? [
    //                 'plan' => StudentSubscription::with('plan')
    //                     ->where('student_id', $studentId)
    //                     ->where('status', 'active')
    //                     ->first()->plan->plan_name,
    //                 'monthly_price' => StudentSubscription::with('plan')
    //                     ->where('student_id', $studentId)
    //                     ->where('status', 'active')
    //                     ->first()->plan->monthly_price,
    //                 'status' => 'active',
    //             ] : null,
    //     ]);
    // }

public function competitions($id)
{
    $entries = DB::table('competition_entries as ce')
        ->leftJoin('competitions as c', 'ce.competition_id', '=', 'c.id')
        ->leftJoin('instructors as i', 'ce.instructor_id', '=', 'i.id')
        ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
        ->select(
            'ce.*',
            'c.name as competition_name',
            'c.date as competition_date',
            DB::raw("CONCAT(u.fname, ' ', u.lname) as instructor_name")
        )
        ->where('ce.student_id', $id)
        ->orderBy('c.date', 'desc')
        ->get();

    return response()->json($entries);
}

 public function show($id)
    {
        $student = DB::table('student_overview')
            ->where('id', $id)
            ->first();

        if (!$student) {
            return response()->json([
                'error' => 'Student not found'
            ], 404);
        }

        return response()->json($student);
    }

    // // ✅ ATTENDANCE DATA
    // public function attendance($id)
    // {
    //     $attendance = DB::table('attendances') // 🔁 change if your table name is different
    //         ->where('student_id', $id)
    //         ->orderBy('checkin_time', 'desc')
    //         ->get();

    //     return response()->json($attendance);
    // }
}
