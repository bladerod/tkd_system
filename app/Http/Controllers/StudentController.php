<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Instructor;
use App\Models\SkillChecklist;

class StudentController extends Controller
{
    public function index()
    {
        // Fetch students with related data based on your schema
        $students = Student::with(['primaryParent.user', 'classes', 'subscriptions.plan'])
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
                    'balance' => $this->calculateBalance($student),
                    'attendance_rate' => $this->calculateAttendanceRate($student),
                    'class_id' => $student->classes->first()?->id,
                    'instructor_id' => $student->classes->first()?->primary_instructor_id,
                ];
            });

        // Get unique belt levels from skill_checklist table
        $beltLevels = SkillChecklist::select('belt_level')
            ->distinct()
            ->get();

        // Get active classes
        $classes = Classes::where('status', 'active')->get();

        // Get active instructors with user info
        $instructors = \App\Models\Instructor::with('user')
            ->where('active_flag', true)
            ->get();

        // Return the view - NOTE: 'student' not 'students.index'
        return view('student', compact('students', 'beltLevels', 'classes', 'instructors'));
    }

    private function calculateBalance($student)
    {
        // Based on your invoices and payments schema
        $totalDue = \App\Models\Invoice::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->sum('total_due');

        $totalPaid = \App\Models\Payment::whereHas('invoice', function($query) use ($student) {
                $query->where('student_id', $student->id);
            })->sum('amount');

        return max(0, $totalDue - $totalPaid);
    }

    private function calculateAttendanceRate($student)
    {
        $total = \App\Models\AttendanceLog::where('student_id', $student->id)->count();
        $present = \App\Models\AttendanceLog::where('student_id', $student->id)
            ->where('status', 'present')
            ->count();

        return $total > 0 ? round(($present / $total) * 100, 1) : 0;
    }

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
        $logs = \App\Models\AttendanceLog::with('classSession.class')
            ->where('student_id', $studentId)
            ->orderBy('checkin_time', 'desc')
            ->paginate(10);

        $stats = [
            'total_sessions' => \App\Models\AttendanceLog::where('student_id', $studentId)->count(),
            'present' => \App\Models\AttendanceLog::where('student_id', $studentId)->where('status', 'present')->count(),
            'late' => \App\Models\AttendanceLog::where('student_id', $studentId)->where('status', 'late')->count(),
            'absent' => \App\Models\AttendanceLog::where('student_id', $studentId)->where('status', 'absent')->count(),
            'excused' => \App\Models\AttendanceLog::where('student_id', $studentId)->where('status', 'excused')->count(),
            'by_method' => [
                'face' => \App\Models\AttendanceLog::where('student_id', $studentId)->where('method', 'face')->count(),
                'qr' => \App\Models\AttendanceLog::where('student_id', $studentId)->where('method', 'qr')->count(),
                'manual' => \App\Models\AttendanceLog::where('student_id', $studentId)->where('method', 'manual')->count(),
            ],
        ];

        return response()->json([
            'stats' => $stats,
            'logs' => $logs,
        ]);
    }

    public function billing($studentId)
    {
        $invoices = \App\Models\Invoice::with('payments')
            ->where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_paid' => $invoices->flatMap->payments->sum('amount'),
            'total_pending' => $invoices->where('status', 'pending')->sum('total_due'),
            'total_overdue' => $invoices->where('status', 'overdue')->sum('total_due'),
            'lifetime_total' => $invoices->sum('total_due'),
        ];

        return response()->json([
            'summary' => $summary,
            'invoices' => $invoices->map(function($inv) {
                return [
                    'invoice_no' => $inv->invoice_no,
                    'status' => $inv->status,
                    'period' => $inv->billing_period_start?->format('M Y') . ' - ' . $inv->billing_period_end?->format('M Y'),
                    'due_date' => $inv->due_date?->format('M d, Y'),
                    'total_due' => $inv->total_due,
                    'payments' => $inv->payments->map(function($p) {
                        return [
                            'amount' => $p->amount,
                            'method' => $p->payment_method,
                            'date' => $p->paid_at,
                        ];
                    }),
                ];
            }),
            'current_subscription' => \App\Models\StudentSubscription::with('plan')
                ->where('student_id', $studentId)
                ->where('status', 'active')
                ->first()?->plan ? [
                    'plan' => \App\Models\StudentSubscription::with('plan')
                        ->where('student_id', $studentId)
                        ->where('status', 'active')
                        ->first()->plan->plan_name,
                    'monthly_price' => \App\Models\StudentSubscription::with('plan')
                        ->where('student_id', $studentId)
                        ->where('status', 'active')
                        ->first()->plan->monthly_price,
                    'status' => 'active',
                ] : null,
        ]);
    }

    public function competitions($studentId)
    {
        $entries = \App\Models\CompetitionEntry::with('competition', 'instructor.user')
            ->where('student_id', $studentId)
            ->get();

        $stats = [
            'gold' => $entries->where('medal', 'gold')->count(),
            'silver' => $entries->where('medal', 'silver')->count(),
            'bronze' => $entries->where('medal', 'bronze')->count(),
            'total_competitions' => $entries->count(),
        ];

        return response()->json([
            'stats' => $stats,
            'entries' => $entries->map(function($entry) {
                return [
                    'competition_name' => $entry->competition->name,
                    'location' => $entry->competition->location,
                    'date' => $entry->competition->date?->format('M d, Y'),
                    'category' => $entry->category,
                    'division' => $entry->division,
                    'result' => $entry->result,
                    'medal' => $entry->medal,
                    'instructor' => $entry->instructor?->user?->name ?? 'N/A',
                    'remarks' => $entry->remarks,
                ];
            }),
        ]);
    }

    public function certificates($studentId)
    {
        $certs = \App\Models\Certificate::where('student_id', $studentId)
            ->orderBy('issued_date', 'desc')
            ->get();

        return response()->json([
            'belt_promotions' => $certs->where('certificate_type', 'belt_promotion')->count(),
            'competition_certs' => $certs->where('certificate_type', 'competition')->count(),
            'participation_certs' => $certs->where('certificate_type', 'participation')->count(),
            'certificates' => $certs->map(function($cert) {
                return [
                    'title' => $cert->title,
                    'certificate_type' => $cert->certificate_type,
                    'description' => $cert->description,
                    'issued_date' => $cert->issued_date?->format('M d, Y'),
                    'issued_by' => \App\Models\User::find($cert->issued_by_user_id)?->name ?? 'System',
                    'pdf_path' => $cert->pdf_path ? asset('storage/' . $cert->pdf_path) : null,
                    'qr_code_value' => $cert->qr_code_value,
                ];
            }),
        ]);
    }

    public function progress($studentId)
    {
        $student = Student::findOrFail($studentId);

        $skills = \App\Models\StudentSkillProgress::with('skill')
            ->where('student_id', $studentId)
            ->get();

        $evaluations = \App\Models\StudentEvaluation::with('instructor.user')
            ->where('student_id', $studentId)
            ->orderBy('evaluation_date', 'desc')
            ->take(5)
            ->get();

        $examHistory = \App\Models\BeltExamResult::with('exam')
            ->where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSkills = $skills->count();
        $mastered = $skills->where('status', 'mastered')->count();
        $inProgress = $skills->where('status', 'in_progress')->count();

        return response()->json([
            'current_belt' => $student->current_belt,
            'progress_summary' => [
                'percentage' => $totalSkills > 0 ? round(($mastered / $totalSkills) * 100) : 0,
                'mastered' => $mastered,
                'in_progress' => $inProgress,
                'total' => $totalSkills,
            ],
            'skills' => $skills->map(function($s) {
                return [
                    'skill_name' => $s->skill->skill_name,
                    'description' => $s->skill->description,
                    'status' => $s->status,
                ];
            }),
            'evaluations' => $evaluations->map(function($e) {
                return [
                    'evaluation_date' => $e->evaluation_date?->format('M d, Y'),
                    'instructor' => $e->instructor?->user?->name ?? 'N/A',
                    'technique_score' => $e->technique_score,
                    'discipline_score' => $e->discipline_score,
                    'fitness_score' => $e->fitness_score,
                    'sparring_score' => $e->sparring_score,
                    'belt_ready_flag' => $e->belt_ready_flag,
                ];
            }),
            'exam_history' => $examHistory->map(function($e) {
                return [
                    'belt_level' => $e->exam->belt_level,
                    'result' => $e->result,
                    'exam_date' => $e->exam->exam_date?->format('M d, Y'),
                    'score' => $e->score,
                    'approved_by' => \App\Models\User::find($e->approved_by)?->name ?? 'N/A',
                ];
            }),
        ]);
    }

    public function chat($studentId)
    {
        $student = Student::with(['chatThread.messages.sender', 'chatThread.participants'])->findOrFail($studentId);

        return response()->json([
            'chat_thread_id' => $student->chatThread?->id,
            'chat_messages' => $student->chatThread?->messages->map(function($msg) {
                return [
                    'sender_user_id' => $msg->sender_user_id,
                    'sender' => ['name' => $msg->sender->name],
                    'message' => $msg->message,
                    'sent_at' => $msg->sent_at,
                ];
            }) ?? [],
        ]);
    }

    public function documents($studentId)
    {
        return response()->json([
            'available_documents' => [
                'enrollment_form' => 'Enrollment Form',
                'waiver' => 'Waiver Form',
                'medical_clearance' => 'Medical Clearance',
                'belt_certificate' => 'Belt Certificate',
                'report_card' => 'Progress Report',
            ],
            'generated_documents' => [], // Populate from your document generation table
        ]);
    }
}
