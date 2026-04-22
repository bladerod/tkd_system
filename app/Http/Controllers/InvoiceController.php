<?php

namespace App\Http\Controllers;

use App\Models\BillingRules;
use App\Models\Discount;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Student;
use App\Models\StudentSubscription;
use App\Models\Classes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display invoices page
     */
    public function index()
    {
        $invoices = Invoice::with(['student.parent.user', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $students = Student::with('parent.user')
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();
        
        $discounts = Discount::all()->where('status', 1);
        $plans = Plan::where('active_flag', 1)
            ->orderBy('plan_name')
            ->get();
        $classes = Classes::where('status', 'active')->orderBy('class_name')->get();
        
        return view('billing', compact('invoices', 'students', 'plans', 'discounts', 'classes'));
    }

    /**
     * Get discount amount based on discount type and student
     */
    public function getDiscountAmount(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'discount_type' => 'required|string',
            'custom_amount' => 'nullable|numeric|min:0'
        ]);
        
        $student = Student::findOrFail($request->student_id);
        $discountAmount = 0;
        $discountReason = '';
        
        switch ($request->discount_type) {
            case 'family':
                // Check for family discount (multiple students from same parent)
                if ($student->primary_parent_id) {
                    $siblingsCount = Student::where('primary_parent_id', $student->primary_parent_id)
                        ->where('status', 'active')
                        ->count();
                    
                    if ($siblingsCount >= 2) {
                        $discountAmount = 100; // ₱100 family discount
                        $discountReason = 'Family discount for ' . $siblingsCount . ' students';
                    } elseif ($siblingsCount == 1) {
                        $discountAmount = 50; // ₱50 discount for first student
                        $discountReason = 'Single student family discount';
                    }
                }
                break;
                
            case 'earlybird':
                // Check if student joined recently (first 3 months)
                $joinDate = Carbon::parse($student->join_date);
                $monthsSinceJoin = $joinDate->diffInMonths(Carbon::now());
                if ($monthsSinceJoin <= 3) {
                    $discountAmount = 200;
                    $discountReason = 'Early bird discount for new students';
                }
                break;
                
            case 'veteran':
                // Check for long-term students (more than 12 months)
                $joinDate = Carbon::parse($student->join_date);
                $monthsSinceJoin = $joinDate->diffInMonths(Carbon::now());
                if ($monthsSinceJoin >= 12) {
                    $discountAmount = 150;
                    $discountReason = 'Veteran student discount';
                }
                break;
                
            case 'referral':
                // Check if student was referred
                // You can add a referral field to students table
                $discountAmount = 100;
                $discountReason = 'Referral discount';
                break;
                
            case 'custom':
                $discountAmount = $request->custom_amount ?? 0;
                $discountReason = 'Custom discount applied';
                break;
                
            default:
                $discountAmount = 0;
                $discountReason = 'No discount';
        }
        
        return response()->json([
            'success' => true,
            'discount_amount' => $discountAmount,
            'discount_reason' => $discountReason
        ]);
    }

    /**
     * Get penalty amount based on billing rules
     */
    public function getPenaltyAmount(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id'
        ]);
        
        $student = Student::findOrFail($request->student_id);
        $rules = BillingRules::first();
        $penalty = 0;
        
        if ($rules) {
            // Check for overdue invoices
            $overdueInvoices = Invoice::where('student_id', $student->id)
                ->where('status', 'overdue')
                ->where('due_date', '<', Carbon::now())
                ->get();
            
            if ($overdueInvoices->count() > 0) {
                if ($rules->late_fees_type === 'Percentage') {
                    $percentage = (float) str_replace('%', '', $rules->late_fee_amount);
                    $penalty = ($rules->monthly_fee ?? 0) * ($percentage / 100);
                } else {
                    $penalty = (float) $rules->late_fee_amount;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'penalty_amount' => $penalty
        ]);
    }

    /**
     * Create a new invoice manually
     */
    public function createInvoice(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'plan_id' => 'required|exists:plans,id',
            'billing_period_start' => 'required|date',
            'billing_period_end' => 'required|date|after_or_equal:billing_period_start',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'penalty' => 'nullable|numeric|min:0',
            'due_date' => 'required|date'
        ]);
        
        DB::beginTransaction();
        
        try {
            $student = Student::findOrFail($request->student_id);
            $plan = Plan::findOrFail($request->plan_id);
            
            // Create or update subscription
            $subscription = StudentSubscription::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'status' => 'active'
                ],
                [
                    'plan_id' => $plan->id,
                    'start_date' => $request->billing_period_start,
                    'end_date' => $request->billing_period_end,
                    'auto_renew_flag' => 1
                ]
            );
            
            $amount = $request->amount;
            $discount = $request->discount ?? 0;
            $penalty = $request->penalty ?? 0;
            $totalDue = $amount - $discount + $penalty;
            
            // Generate invoice number
            $invoiceNo = $this->generateInvoiceNumber();
            
            $invoice = Invoice::create([
                'student_id' => $student->id,
                'parent_id' => $student->primary_parent_id,
                'subscription_id' => $subscription->id,
                'invoice_no' => $invoiceNo,
                'billing_period_start' => $request->billing_period_start,
                'billing_period_end' => $request->billing_period_end,
                'amount' => $amount,
                'discount' => $discount,
                'penalty' => $penalty,
                'total_due' => $totalDue,
                'due_date' => $request->due_date,
                'status' => 'pending'
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'invoice' => $invoice,
                'message' => 'Invoice created successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate invoice for a student (auto-generate)
     */
    public function generateInvoice($studentId, $subscriptionId = null)
    {
        DB::beginTransaction();
        
        try {
            $student = Student::findOrFail($studentId);
            $rules = BillingRules::first();
            
            // Get active subscription or use default
            if (!$subscriptionId) {
                $subscription = StudentSubscription::where('student_id', $studentId)
                    ->where('status', 'active')
                    ->first();
            } else {
                $subscription = StudentSubscription::findOrFail($subscriptionId);
            }
            
            // Calculate invoice amounts
            $amount = $rules->monthly_fee ?? 0;
            $discount = $this->calculateDiscount($student, $subscription);
            $penalty = $this->calculatePenalty($student);
            
            $totalDue = $amount - $discount + $penalty;
            
            // Generate invoice number
            $invoiceNo = $this->generateInvoiceNumber();
            
            // Calculate due date based on rules
            $dueDate = $this->calculateDueDate($rules);
            
            $invoice = Invoice::create([
                'student_id' => $studentId,
                'parent_id' => $student->primary_parent_id,
                'subscription_id' => $subscription ? $subscription->id : null,
                'invoice_no' => $invoiceNo,
                'billing_period_start' => Carbon::now()->startOfMonth(),
                'billing_period_end' => Carbon::now()->endOfMonth(),
                'amount' => $amount,
                'discount' => $discount,
                'penalty' => $penalty,
                'total_due' => $totalDue,
                'due_date' => $dueDate,
                'status' => 'pending'
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'invoice' => $invoice
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get invoices as JSON for dashboard
     */
    public function getInvoicesJson()
    {
        $invoices = Invoice::with(['student'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'student_name' => $invoice->student->first_name . ' ' . $invoice->student->last_name,
                    'total_due' => $invoice->total_due,
                    'status' => $invoice->status,
                ];
            });
        
        return response()->json([
            'success' => true,
            'invoices' => $invoices
        ]);
    }


    /**
     * Auto-generate monthly invoices for all active students
     */
    public function generateMonthlyInvoices()
    {
        DB::beginTransaction();
        
        try {
            $rules = BillingRules::first();
            
            // Check if auto-generate is enabled
            if (!$rules || !$rules->auto_generate_monthly_invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Auto-generation is disabled'
                ]);
            }
            
            // Get all active students
            $activeStudents = Student::where('status', 'active')->get();
            $generatedCount = 0;
            
            foreach ($activeStudents as $student) {
                // Check if invoice already exists for this month
                $existingInvoice = Invoice::where('student_id', $student->id)
                    ->whereYear('billing_period_start', Carbon::now()->year)
                    ->whereMonth('billing_period_start', Carbon::now()->month)
                    ->first();
                
                if (!$existingInvoice) {
                    $this->generateInvoice($student->id);
                    $generatedCount++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'generated' => $generatedCount,
                'message' => "Generated {$generatedCount} invoices"
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process payment for an invoice
     */
    public function processPayment(Request $request, $invoiceId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card',
            'transaction_reference' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        
        try {
            $invoice = Invoice::with('student')->findOrFail($invoiceId);
            $rules = BillingRules::first();
            
            // Check if partial payments are allowed
            $isPartial = $request->amount < $invoice->total_due;
            if ($isPartial && (!$rules || !$rules->allow_partial_payment)) {
                throw new \Exception('Partial payments are not allowed');
            }
            
            // Create payment record
            $payment = Payment::create([
                'invoice_id' => $invoiceId,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'transaction_reference' => $request->transaction_reference,
                'paid_at' => Carbon::now(),
                'status' => 'completed'
            ]);
            
            // Update invoice status
            $totalPaid = $invoice->payments()->sum('amount') + $request->amount;
            
            if ($totalPaid >= $invoice->total_due) {
                $invoice->status = 'paid';
            } else {
                $invoice->status = 'partial';
            }
            
            $invoice->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'payment' => $payment,
                'invoice' => $invoice,
                'message' => 'Payment processed successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark overdue invoices
     */
    public function markOverdueInvoices()
    {
        $rules = BillingRules::first();
        
        if (!$rules || !$rules->auto_mark_overdue) {
            return response()->json([
                'success' => false,
                'message' => 'Auto-mark overdue is disabled'
            ]);
        }
        
        $overdueInvoices = Invoice::where('status', 'pending')
            ->orWhere('status', 'partial')
            ->where('due_date', '<', Carbon::now())
            ->update(['status' => 'overdue']);
        
        return response()->json([
            'success' => true,
            'updated' => $overdueInvoices,
            'message' => "Marked {$overdueInvoices} invoices as overdue"
        ]);
    }

    /**
     * Send payment reminder
     */
    public function sendReminder($invoiceId)
    {
        $invoice = Invoice::with(['student', 'student.parent'])
            ->findOrFail($invoiceId);
        
        // You would implement email/SMS notification here
        // For now, just log it
        
        Log::info('Payment reminder sent', [
            'invoice_no' => $invoice->invoice_no,
            'student' => $invoice->student->name,
            'amount_due' => $invoice->total_due - $invoice->payments()->sum('amount')
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Reminder sent successfully'
        ]);
    }

    /**
     * Generate receipt PDF (simplified version)
     */
    public function generateReceipt($invoiceId)
    {
        $invoice = Invoice::with(['student', 'student.parent', 'payments'])
            ->findOrFail($invoiceId);
        
        // You would use a PDF library like DomPDF here
        // For now, return the data
        
        return view('receipt', compact('invoice'));
    }

    /**
     * Calculate discount for a student
     */
    private function calculateDiscount($student, $subscription)
    {
        $discount = 0;
        
        // Family discount logic - you can customize this
        // Check if multiple students from same family
        if ($student->parent_id) {
            $siblingsCount = Student::where('parent_id', $student->parent_id)
                ->where('status', 'active')
                ->count();
            
            if ($siblingsCount >= 2) {
                $discount = 100; // ₱100 family discount
            }
        }
        
        return $discount;
    }

    /**
     * Calculate penalty for overdue payments
     */
    private function calculatePenalty($student)
    {
        $rules = BillingRules::first();
        
        if (!$rules) return 0;
        
        // Check for overdue invoices
        $overdueInvoices = Invoice::where('student_id', $student->id)
            ->where('status', 'overdue')
            ->where('due_date', '<', Carbon::now())
            ->get();
        
        if ($overdueInvoices->count() > 0) {
            $lateFeeAmount = $rules->late_fee_amount;
            
            if ($rules->late_fees_type === 'Percentage') {
                // Remove % sign if present
                $percentage = (float) str_replace('%', '', $lateFeeAmount);
                return ($rules->monthly_fee ?? 0) * ($percentage / 100);
            } else {
                return (float) $lateFeeAmount;
            }
        }
        
        return 0;
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_no, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "INV-{$year}{$month}-{$newNumber}";
    }

    /**
     * Calculate due date based on rules
     */
    private function calculateDueDate($rules)
    {
        $dueDateRule = $rules->due_date_rule ?? '1st of Month';
        
        switch ($dueDateRule) {
            case '1st of Month':
                return Carbon::now()->startOfMonth()->addDays(0);
            case '5th of Month':
                return Carbon::now()->startOfMonth()->addDays(4);
            case '10th of Month':
                return Carbon::now()->startOfMonth()->addDays(9);
            case '15th of Month':
                return Carbon::now()->startOfMonth()->addDays(14);
            default:
                return Carbon::now()->addDays(15);
        }
    }
}