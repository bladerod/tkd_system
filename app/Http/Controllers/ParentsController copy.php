<?php
// app/Http/Controllers/ParentController.php
namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ChatThread;
use App\Models\ChatMessage;
use App\Models\Notification;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function index()
    {
        $parentList = ParentModel::with(['user', 'students' => function($q) {
                $q->withCount(['invoices' => function($q) {
                    $q->where('status', 'pending');
                }]);
            }])
            ->withCount('students')
            ->get()
            ->map(function($parent) {
                return [
                    'id' => $parent->id,
                    'fname' => $parent->user->name,
                    'mobile' => $parent->user->mobile,
                    'email' => $parent->user->email,
                    'status' => $parent->user->status,
                    'children_count' => $parent->students_count,
                    'total_balance' => $this->calculateTotalBalance($parent),
                    'id_verified' => $parent->id_verified_flag,
                    'address' => $parent->address,
                    'emergency_contact' => $parent->emergency_contact,
                    'students' => $parent->students,
                    'user_id' => $parent->user_id
                ];
            });

        return view('parent.index', compact('parentList'));
    }

    public function show($id)
    {
        $parent = ParentModel::with([
            'user',
            'students.subscriptions.plan',
            'students.invoices' => function($q) {
                $q->orderBy('due_date', 'desc');
            },
            'students.invoices.payments',
            'students.classes',
            'students.attendanceLogs' => function($q) {
                $q->latest()->limit(10);
            },
            'students.certificates',
            'students.evaluations'
        ])->findOrFail($id);

        // Get or create chat thread for this parent
        $chatThread = ChatThread::whereHas('participants', function($q) use ($parent) {
            $q->where('user_id', $parent->user_id);
        })->where('type', 'parent-staff')->first();

        if (!$chatThread) {
            $chatThread = ChatThread::create(['type' => 'parent-staff']);
            $chatThread->participants()->attach([$parent->user_id, Auth::id()]);
        }

        $chatMessages = $chatThread->messages()->with('sender')->latest()->limit(50)->get()->reverse();

        // Get notifications for this parent
        $notifications = Notification::where('user_id', $parent->user_id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get activity logs
        $activityLogs = AuditLog::where('user_id', $parent->user_id)
            ->orWhereIn('entity_id', $parent->students->pluck('id'))
            ->where('entity', 'student')
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get();

        // Calculate family billing summary
        $billingSummary = $this->getFamilyBillingSummary($parent);

        return response()->json([
            'parent' => $parent,
            'students' => $parent->students,
            'chat_thread_id' => $chatThread->id,
            'chat_messages' => $chatMessages,
            'notifications' => $notifications,
            'activity_logs' => $activityLogs,
            'billing_summary' => $billingSummary
        ]);
    }

    public function sendMessage(Request $request, $parentId)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $parent = ParentModel::findOrFail($parentId);

        $thread = ChatThread::whereHas('participants', function($q) use ($parent) {
            $q->where('user_id', $parent->user_id);
        })->first();

        if (!$thread) {
            return response()->json(['error' => 'Chat thread not found'], 404);
        }

        $message = ChatMessage::create([
            'thread_id' => $thread->id,
            'sender_user_id' => Auth::id(),
            'message' => $request->message,
            'sent_at' => now()
        ]);

        // Create notification for parent
        Notification::create([
            'user_id' => $parent->user_id,
            'title' => 'New message from ' . Auth::user()->name,
            'message' => substr($request->message, 0, 100),
            'type' => 'chat',
            'read_flag' => false
        ]);

        return response()->json(['success' => true, 'message' => $message->load('sender')]);
    }

    public function getChildrenDetails($parentId)
    {
        $parent = ParentModel::with([
            'students.subscriptions.plan',
            'students.classes.class',
            'students.currentBelt',
            'students.skillProgress.skill',
            'students.evaluations' => function($q) {
                $q->latest()->limit(5);
            }
        ])->findOrFail($parentId);

        $childrenData = $parent->students->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'student_code' => $student->student_code,
                'current_belt' => $student->current_belt,
                'age' => now()->diffInYears($student->birthdate),
                'status' => $student->status,
                'photo' => $student->photo_url,
                'classes' => $student->classes->map(fn($c) => $c->class->class_name),
                'subscription' => $student->subscriptions->first()?->plan->plan_name ?? 'No active plan',
                'subscription_status' => $student->subscriptions->first()?->status ?? 'N/A',
                'skills_mastered' => $student->skillProgress->where('status', 'mastered')->count(),
                'total_skills' => $student->skillProgress->count(),
                'last_evaluation' => $student->evaluations->first()?->evaluation_date,
                'attendance_rate' => $this->calculateAttendanceRate($student)
            ];
        });

        return response()->json(['children' => $childrenData]);
    }

    public function getFamilyBilling($parentId)
    {
        $parent = ParentModel::with(['students.invoices.payments'])->findOrFail($parentId);

        $allInvoices = collect();
        foreach ($parent->students as $student) {
            $allInvoices = $allInvoices->merge($student->invoices);
        }

        $billingData = [
            'total_outstanding' => $allInvoices->where('status', 'pending')->sum('total_due'),
            'total_paid_this_month' => $allInvoices->where('status', 'paid')
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('amount'),
            'overdue_amount' => $allInvoices->where('status', 'overdue')->sum('total_due'),
            'invoices_by_student' => $parent->students->map(function($student) {
                return [
                    'student_name' => $student->first_name . ' ' . $student->last_name,
                    'invoices' => $student->invoices->map(fn($inv) => [
                        'id' => $inv->id,
                        'invoice_no' => $inv->invoice_no,
                        'amount' => $inv->amount,
                        'total_due' => $inv->total_due,
                        'due_date' => $inv->due_date,
                        'status' => $inv->status,
                        'billing_period' => $inv->billing_period_start . ' to ' . $inv->billing_period_end
                    ])
                ];
            }),
            'recent_payments' => Payment::whereIn('invoice_id', $allInvoices->pluck('id'))
                ->with('invoice.student')
                ->latest()
                ->limit(10)
                ->get()
                ->map(fn($p) => [
                    'date' => $p->paid_at,
                    'amount' => $p->amount,
                    'method' => $p->payment_method,
                    'student' => $p->invoice->student->first_name,
                    'reference' => $p->reference_no
                ])
        ];

        return response()->json($billingData);
    }

    public function getPayments($parentId)
    {
        $parent = ParentModel::findOrFail($parentId);
        $studentIds = $parent->students->pluck('id');

        $payments = Payment::whereHas('invoice', function($q) use ($studentIds) {
                $q->whereIn('student_id', $studentIds);
            })
            ->with(['invoice.student', 'receivedBy'])
            ->latest()
            ->paginate(20);

        return response()->json(['payments' => $payments]);
    }

    public function getActivityLog($parentId)
    {
        $parent = ParentModel::findOrFail($parentId);
        $studentIds = $parent->students->pluck('id');

        $logs = AuditLog::where(function($q) use ($parent, $studentIds) {
                $q->where('user_id', $parent->user_id)
                  ->orWhere(function($q) use ($studentIds) {
                      $q->where('entity', 'student')
                        ->whereIn('entity_id', $studentIds);
                  });
            })
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(fn($log) => [
                'action' => $log->action,
                'entity' => $log->entity,
                'description' => $this->formatAuditLog($log),
                'timestamp' => $log->created_at,
                'user' => $log->user?->name ?? 'System'
            ]);

        return response()->json(['logs' => $logs]);
    }

    public function getNotifications($parentId)
    {
        $parent = ParentModel::findOrFail($parentId);

        $notifications = Notification::where('user_id', $parent->user_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'type' => $n->type,
                'read' => $n->read_flag,
                'time' => $n->created_at->diffForHumans()
            ]);

        // Mark as read
        Notification::where('user_id', $parent->user_id)
            ->where('read_flag', false)
            ->update(['read_flag' => true]);

        return response()->json(['notifications' => $notifications]);
    }

    // Helper methods
    private function calculateTotalBalance($parent)
    {
        $total = 0;
        foreach ($parent->students as $student) {
            $total += $student->invoices()
                ->whereIn('status', ['pending', 'overdue'])
                ->sum('total_due');
        }
        return $total;
    }

    private function getFamilyBillingSummary($parent)
    {
        $studentIds = $parent->students->pluck('id');
        $invoices = Invoice::whereIn('student_id', $studentIds)->get();

        return [
            'total_invoices' => $invoices->count(),
            'total_paid' => $invoices->where('status', 'paid')->sum('total_due'),
            'total_pending' => $invoices->where('status', 'pending')->sum('total_due'),
            'total_overdue' => $invoices->where('status', 'overdue')->sum('total_due'),
            'monthly_average' => $invoices->avg('total_due')
        ];
    }

    private function calculateAttendanceRate($student)
    {
        $total = $student->attendanceLogs()->count();
        if ($total === 0) return 0;

        $present = $student->attendanceLogs()
            ->whereIn('status', ['present', 'late'])
            ->count();

        return round(($present / $total) * 100, 1);
    }

    private function formatAuditLog($log)
    {
        return match($log->action) {
            'create' => "Created new {$log->entity}",
            'update' => "Updated {$log->entity} details",
            'delete' => "Removed {$log->entity}",
            'payment' => "Recorded payment",
            'attendance' => "Marked attendance",
            default => "Performed action on {$log->entity}"
        };
    }
}
