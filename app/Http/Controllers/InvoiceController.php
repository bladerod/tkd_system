<?php

namespace App\Http\Controllers;

use App\Models\BillingRules;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentSubscription;
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
        $invoices = Invoice::with(['student.parent.user', 'payments', ])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('billing', compact('invoices'));
    }

    /**
     * Generate invoice for a student
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