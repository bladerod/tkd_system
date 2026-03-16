<?php
// app/Http/Controllers/StudentController.php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use App\Models\Instructor;
use App\Models\SkillChecklist;
use App\Models\AttendanceLog;
use App\Models\Invoice;
use App\Models\CompetitionEntry;
use App\Models\Certificate;
use App\Models\StudentEvaluation;
use App\Models\ChatThread;
use App\Models\ChatMessage;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $beltLevels = SkillChecklist::select('belt_level')->distinct()->get();
        $classes = Classes::where('status', 'active')->get();
        $instructors = Instructor::where('active_flag', true)->with('user')->get();

        $students = Student::with(['primaryParent.user', 'classes.class', 'invoices', 'attendanceLogs'])
            ->withCount(['attendanceLogs as present_count' => function($q) {
                $q->whereIn('status', ['present', 'late']);
            }])
            ->get()
            ->map(function($student) {
                $totalAttendance = $student->attendanceLogs->count();
                $attendanceRate = $totalAttendance > 0
                    ? round(($student->present_count / $totalAttendance) * 100, 0)
                    : 0;

                $balance = $student->invoices()
                    ->whereIn('status', ['pending', 'overdue'])
                    ->sum('total_due');

                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'belt' => $student->current_belt,
                    'status' => $student->status,
                    'parent_name' => $student->primaryParent?->user?->name ?? 'N/A',
                    'parent_id' => $student->primary_parent_id,
                    'balance' => $balance,
                    'attendance_rate' => $attendanceRate,
                    'photo' => $student->photo_url,
                    'student_code' => $student->student_code,
                    'gender' => $student->gender,
                    'age' => now()->diffInYears($student->birthdate),
                    'join_date' => $student->join_date,
                    'medical_notes' => $student->medical_notes,
                    'allergies' => $student->allergies
                ];
            });

        return view('student.index', compact('students', 'beltLevels', 'classes', 'instructors'));
    }

    public function show($id)
    {
        $student = Student::with([
            'primaryParent.user',
            'parents.user',
            'classes.class.schedules',
            'subscriptions.plan',
            'invoices.payments',
            'attendanceLogs.classSession',
            'certificates.issuedBy',
            'evaluations.instructor.user',
            'skillProgress.skill',
            'competitionEntries.competition',
            'competitionEntries.instructor.user',
            'faceProfile'
        ])->findOrFail($id);

        // Get or create chat thread
        $chatThread = ChatThread::whereHas('participants', function($q) use ($student) {
            $q->where('user_id', Auth::id());
        })->whereHas('participants', function($q) use ($student) {
            $q->where('user_id', $student->primaryParent?->user_id);
        })->where('type', 'private')->first();

        if (!$chatThread && $student->primaryParent) {
            $chatThread = ChatThread::create(['type' => 'private']);
            $chatThread->participants()->attach([
                Auth::id(),
                $student->primaryParent->user_id
            ]);
        }

        $chatMessages = $chatThread ? $chatThread->messages()->with('sender')->latest()->limit(50)->get()->reverse() : collect();

        // Calculate progress
        $totalSkills = SkillChecklist::where('belt_level', $student->current_belt)->count();
        $masteredSkills = $student->skillProgress->where('status', 'mastered')->count();
        $progressPercent = $totalSkills > 0 ? round(($masteredSkills / $totalSkills) * 100, 0) : 0;

        // Attendance chart data
        $attendanceByMonth = $this->getAttendanceChartData($student);

        return response()->json([
            'student' => $student,
            'chat_thread_id' => $chatThread?->id,
            'chat_messages' => $chatMessages,
            'progress' => [
                'total_skills' => $totalSkills,
                'mastered' => $masteredSkills,
                'in_progress' => $student->skillProgress->where('status', 'in_progress')->count(),
                'not_started' => $student->skillProgress->where('status', 'not_started')->count(),
                'percent' => $progressPercent
            ],
            'attendance_chart' => $attendanceByMonth,
            'upcoming_sessions' => $this->getUpcomingSessions($student),
            'belt_history' => $this->getBeltHistory($student)
        ]);
    }

    public function getProfile($id)
    {
        $student = Student::with([
            'primaryParent.user',
            'parents.user',
            'classes.class.instructors',
            'subscriptions.plan',
            'faceProfile'
        ])->findOrFail($id);

        return response()->json([
            'profile' => [
                'id' => $student->id,
                'student_code' => $student->student_code,
                'name' => $student->first_name . ' ' . $student->last_name,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'middle_name' => $student->middle_name,
                'birthdate' => $student->birthdate,
                'age' => now()->diffInYears($student->birthdate),
                'gender' => $student->gender,
                'current_belt' => $student->current_belt,
                'join_date' => $student->join_date,
                'status' => $student->status,
                'photo' => $student->photo_url,
                'medical_notes' => $student->medical_notes,
                'allergies' => $student->allergies,
                'emergency_contact' => $student->emergency_contact_name,
                'emergency_mobile' => $student->emergency_contact_mobile
            ],
            'parents' => $student->parents->map(fn($p) => [
                'name' => $p->user->name,
                'relationship' => $p->pivot->relationship,
                'is_primary' => $p->pivot->is_primary,
                'mobile' => $p->user->mobile,
                'email' => $p->user->email
            ]),
            'classes' => $student->classes->map(fn($c) => [
                'name' => $c->class->class_name,
                'level' => $c->class->level,
                'age_group' => $c->class->age_group,
                'instructor' => $c->class->primaryInstructor?->user?->name ?? 'TBA',
                'schedule' => $c->class->schedules->map(fn($s) =>
                    ucfirst($s->day_of_week) . ' ' . $s->start_time . '-' . $s->end_time
                ),
                'enrollment_status' => $c->pivot->status,
                'start_date' => $c->pivot->start_date
            ]),
            'subscription' => $student->subscriptions->first() ? [
                'plan' => $student->subscriptions->first()->plan->plan_name,
                'status' => $student->subscriptions->first()->status,
                'start' => $student->subscriptions->first()->start_date,
                'end' => $student->subscriptions->first()->end_date,
                'auto_renew' => $student->subscriptions->first()->auto_renew_flag
            ] : null
        ]);
    }

    public function getAttendance($id)
    {
        $student = Student::findOrFail($id);

        $logs = $student->attendanceLogs()
            ->with('classSession.class')
            ->latest()
            ->paginate(20);

        $stats = [
            'total_sessions' => $student->attendanceLogs()->count(),
            'present' => $student->attendanceLogs()->where('status', 'present')->count(),
            'late' => $student->attendanceLogs()->where('status', 'late')->count(),
            'absent' => $student->attendanceLogs()->where('status', 'absent')->count(),
            'excused' => $student->attendanceLogs()->where('status', 'excused')->count(),
            'by_method' => [
                'face' => $student->attendanceLogs()->where('method', 'face')->count(),
                'qr' => $student->attendanceLogs()->where('method', 'qr')->count(),
                'manual' => $student->attendanceLogs()->where('method', 'manual')->count()
            ]
        ];

        $monthlyData = $student->attendanceLogs()
            ->selectRaw('DATE_FORMAT(checkin_time, "%Y-%m") as month, COUNT(*) as total, SUM(CASE WHEN status IN ("present", "late") THEN 1 ELSE 0 END) as attended')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'logs' => $logs,
            'stats' => $stats,
            'monthly_trend' => $monthlyData
        ]);
    }

    public function getBilling($id)
    {
        $student = Student::with(['invoices.payments.receivedBy', 'subscriptions.plan'])->findOrFail($id);

        $invoices = $student->invoices()->orderBy('created_at', 'desc')->get();

        $summary = [
            'total_paid' => $invoices->where('status', 'paid')->sum('total_due'),
            'total_pending' => $invoices->where('status', 'pending')->sum('total_due'),
            'total_overdue' => $invoices->where('status', 'overdue')->sum('total_due'),
            'lifetime_total' => $invoices->sum('total_due')
        ];

        return response()->json([
            'invoices' => $invoices->map(fn($inv) => [
                'id' => $inv->id,
                'invoice_no' => $inv->invoice_no,
                'period' => $inv->billing_period_start . ' to ' . $inv->billing_period_end,
                'amount' => $inv->amount,
                'discount' => $inv->discount,
                'penalty' => $inv->penalty,
                'total_due' => $inv->total_due,
                'due_date' => $inv->due_date,
                'status' => $inv->status,
                'created_at' => $inv->created_at,
                'payments' => $inv->payments->map(fn($p) => [
                    'date' => $p->paid_at,
                    'amount' => $p->amount,
                    'method' => $p->payment_method,
                    'reference' => $p->reference_no,
                    'received_by' => $p->receivedBy?->name ?? 'System'
                ])
            ]),
            'summary' => $summary,
            'current_subscription' => $student->subscriptions->first() ? [
                'plan' => $student->subscriptions->first()->plan->plan_name,
                'monthly_price' => $student->subscriptions->first()->plan->monthly_price,
                'status' => $student->subscriptions->first()->status
            ] : null
        ]);
    }

    public function getCompetitions($id)
    {
        $student = Student::findOrFail($id);

        $entries = CompetitionEntry::with(['competition', 'instructor.user'])
            ->where('student_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'entries' => $entries->map(fn($e) => [
                'id' => $e->id,
                'competition_name' => $e->competition->name,
                'location' => $e->competition->location,
                'date' => $e->competition->date,
                'organizer' => $e->competition->organizer,
                'level' => $e->competition->level,
                'category' => $e->category,
                'division' => $e->division,
                'result' => $e->result,
                'medal' => $e->medal,
                'remarks' => $e->remarks,
                'instructor' => $e->instructor?->user?->name ?? 'N/A',
                'registered_at' => $e->created_at
            ]),
            'stats' => [
                'total_competitions' => $entries->count(),
                'gold' => $entries->where('medal', 'gold')->count(),
                'silver' => $entries->where('medal', 'silver')->count(),
                'bronze' => $entries->where('medal', 'bronze')->count(),
                'no_medal' => $entries->where('medal', 'none')->count()
            ]
        ]);
    }

    public function getCertificates($id)
    {
        $student = Student::findOrFail($id);

        $certificates = Certificate::with('issuedBy')
            ->where('student_id', $id)
            ->orderBy('issued_date', 'desc')
            ->get();

        return response()->json([
            'certificates' => $certificates->map(fn($cert) => [
                'id' => $cert->id,
                'type' => $cert->certificate_type,
                'title' => $cert->title,
                'description' => $cert->description,
                'issued_date' => $cert->issued_date,
                'issued_by' => $cert->issuedBy?->name ?? 'System',
                'qr_code' => $cert->qr_code_value,
                'verification_url' => $cert->verification_url,
                'pdf_url' => $cert->pdf_path ? asset('storage/' . $cert->pdf_path) : null
            ]),
            'belt_promotions' => $certificates->where('certificate_type', 'belt_promotion')->count(),
            'competition_certs' => $certificates->where('certificate_type', 'competition')->count(),
            'participation_certs' => $certificates->where('certificate_type', 'participation')->count()
        ]);
    }

    public function getProgress($id)
    {
        $student = Student::with(['skillProgress.instructor.user', 'evaluations.instructor.user'])->findOrFail($id);

        $currentBelt = $student->current_belt;

        // Get all skills for current belt
        $allSkills = SkillChecklist::where('belt_level', $currentBelt)->get();
        $studentProgress = $student->skillProgress->keyBy('skill_id');

        $skills = $allSkills->map(function($skill) use ($studentProgress) {
            $progress = $studentProgress->get($skill->id);
            return [
                'id' => $skill->id,
                'name' => $skill->skill_name,
                'description' => $skill->description,
                'status' => $progress?->status ?? 'not_started',
                'checked_by' => $progress?->instructor?->user?->name ?? null,
                'checked_at' => $progress?->checked_at,
                'belt_level' => $skill->belt_level
            ];
        });

        // Get belt exam history
        $examResults = \App\Models\BeltExamResult::with(['exam', 'approvedBy.user'])
            ->where('student_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'current_belt' => $currentBelt,
            'skills' => $skills,
            'progress_summary' => [
                'total' => $skills->count(),
                'mastered' => $skills->where('status', 'mastered')->count(),
                'in_progress' => $skills->where('status', 'in_progress')->count(),
                'not_started' => $skills->where('status', 'not_started')->count(),
                'percentage' => $skills->count() > 0
                    ? round(($skills->where('status', 'mastered')->count() / $skills->count()) * 100, 0)
                    : 0
            ],
            'evaluations' => $student->evaluations->map(fn($e) => [
                'date' => $e->evaluation_date,
                'instructor' => $e->instructor?->user?->name ?? 'N/A',
                'technique' => $e->technique_score,
                'discipline' => $e->discipline_score,
                'fitness' => $e->fitness_score,
                'sparring' => $e->sparring_score,
                'belt_ready' => $e->belt_ready_flag,
                'notes' => $e->notes
            ]),
            'exam_history' => $examResults->map(fn($r) => [
                'exam_date' => $r->exam->exam_date,
                'belt_level' => $r->exam->belt_level,
                'score' => $r->score,
                'result' => $r->result,
                'remarks' => $r->remarks,
                'approved_by' => $r->approvedBy?->user?->name ?? 'N/A'
            ])
        ]);
    }

    public function getDocuments($id)
    {
        $student = Student::findOrFail($id);

        // Get all document-related audit logs
        $documents = AuditLog::where('entity', 'student')
            ->where('entity_id', $id)
            ->where('action', 'like', '%document%')
            ->orderBy('created_at', 'desc')
            ->get();

        // Simulated document list (you would typically have a documents table)
        $documentTypes = [
            'enrollment_form' => 'Enrollment Form',
            'medical_clearance' => 'Medical Clearance',
            'waiver' => 'Liability Waiver',
            'belt_exam_application' => 'Belt Exam Application',
            'competition_consent' => 'Competition Consent Form'
        ];

        return response()->json([
            'available_documents' => $documentTypes,
            'generated_documents' => $documents->map(fn($d) => [
                'type' => $d->action,
                'generated_at' => $d->created_at,
                'generated_by' => $d->user?->name ?? 'System',
                'details' => $d->new_value
            ])
        ]);
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $student = Student::with('primaryParent')->findOrFail($id);

        if (!$student->primaryParent) {
            return response()->json(['error' => 'Student has no parent contact'], 400);
        }

        $thread = ChatThread::whereHas('participants', function($q) use ($student) {
            $q->where('user_id', $student->primaryParent->user_id);
        })->where('type', 'private')->first();

        if (!$thread) {
            return response()->json(['error' => 'Chat thread not found'], 404);
        }

        $message = ChatMessage::create([
            'thread_id' => $thread->id,
            'sender_user_id' => Auth::id(),
            'message' => $request->message,
            'sent_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => $message->load('sender')]);
    }

    // Helper methods
    private function getAttendanceChartData($student)
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push([
                'month' => $date->format('M Y'),
                'present' => $student->attendanceLogs()
                    ->whereMonth('checkin_time', $date->month)
                    ->whereYear('checkin_time', $date->year)
                    ->where('status', 'present')
                    ->count(),
                'absent' => $student->attendanceLogs()
                    ->whereMonth('checkin_time', $date->month)
                    ->whereYear('checkin_time', $date->year)
                    ->where('status', 'absent')
                    ->count()
            ]);
        }
        return $months;
    }

    private function getUpcomingSessions($student)
    {
        $classIds = $student->classes->pluck('class_id');

        return \App\Models\ClassSession::with('class')
            ->whereIn('class_id', $classIds)
            ->where('session_date', '>=', now())
            ->where('session_status', 'scheduled')
            ->orderBy('session_date')
            ->limit(5)
            ->get()
            ->map(fn($s) => [
                'date' => $s->session_date,
                'time' => $s->start_time . ' - ' . $s->end_time,
                'class_name' => $s->class->class_name,
                'instructor' => $s->instructor?->user?->name ?? 'TBA'
            ]);
    }

    private function getBeltHistory($student)
    {
        return Certificate::where('student_id', $student->id)
            ->where('certificate_type', 'belt_promotion')
            ->orderBy('issued_date', 'desc')
            ->get()
            ->map(fn($c) => [
                'belt' => str_replace('Belt Promotion - ', '', $c->title),
                'date' => $c->issued_date,
                'issued_by' => $c->issuedBy?->name ?? 'System'
            ]);
    }
}
