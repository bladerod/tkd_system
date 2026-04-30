<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingApiController extends Controller
{
    public function studentBilling(Request $request)
    {
        $user = $request->user();
        $student = \DB::table('students')->where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        return $this->getBillingData($student->id);
    }

    public function parentBilling(Request $request, $studentId)
    {
        $student = \DB::table('students')->where('id', $studentId)->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        \Log::info('Student billing called', ['student_id' => $student->id]);

        return $this->getBillingData($studentId);
    }

    private function getBillingData($studentId)
    {
        // Get active subscription + plan
        $subscription = \DB::table('student_subscriptions')
            ->join('plans', 'student_subscriptions.plan_id', '=', 'plans.id')
            ->where('student_subscriptions.student_id', $studentId)
            ->where('student_subscriptions.status', 'active')
            ->select(
                'plans.plan_name',
                'plans.monthly_price',
                'plans.billing_cycle',
                'plans.description',
                'student_subscriptions.end_date'
            )
            ->first();

        // Get invoices
        $invoices = \DB::table('invoices')
            ->where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($inv) {
                $date = \Carbon\Carbon::parse($inv->billing_period_start);
                return [
                    'id'         => $inv->id,
                    'invoice_no' => $inv->invoice_no,
                    'month'      => strtoupper($date->format('M')),
                    'year'       => $date->format('Y'),
                    'amount'     => $inv->total_due,
                    'date'       => $date->format('F j, Y'),
                    'due_date'   => \Carbon\Carbon::parse($inv->due_date)->format('M j, Y'),
                    'status'     => $inv->status,
                    'payment_proof' => $inv->payment_proof,
                ];
            });

        // Get payment history
        $payments = \DB::table('payments')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->where('invoices.student_id', $studentId)
            ->where('payments.status', 'completed')
            ->orderBy('payments.paid_at', 'desc')
            ->select(
                'payments.id',
                'payments.amount',
                'payments.payment_method',
                'payments.paid_at',
                'invoices.invoice_no'
            )
            ->get()
            ->map(function ($p) {
                $date = \Carbon\Carbon::parse($p->paid_at);
                return [
                    'id'             => $p->id,
                    'label'          => $p->invoice_no,
                    'payment_method' => ucfirst(str_replace('_', ' ', $p->payment_method)),
                    'date'           => $date->format('M j, Y'),
                    'amount'         => $p->amount,
                ];
            });

        // Get current balance (unpaid invoices)
        $balance = \DB::table('invoices')
            ->where('student_id', $studentId)
            ->whereIn('status', ['pending', 'overdue'])
            ->sum('total_due');

        // Get pending invoice
        $pendingInvoice = \DB::table('invoices')
            ->where('student_id', $studentId)
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date', 'asc')
            ->first();

        return response()->json([
            'success' => true,
            'data'    => [
                'plan' => $subscription ? [
                    'name'          => $subscription->plan_name,
                    'amount'        => $subscription->monthly_price,
                    'description'   => $subscription->description,
                    'billing_cycle' => ucfirst($subscription->billing_cycle),
                    'next_due'      => $subscription->end_date
                        ? \Carbon\Carbon::parse($subscription->end_date)->format('M j')
                        : 'N/A',
                ] : null,
                'balance'         => $balance,
                'invoices'        => $invoices,
                'payment_history' => $payments,
                'pending_invoice' => $pendingInvoice ? [
                    'id'       => $pendingInvoice->id,
                    'amount'   => $pendingInvoice->total_due,
                    'due_date' => \Carbon\Carbon::parse($pendingInvoice->due_date)->format('M j, Y'),
                    'status'   => $pendingInvoice->status,
                    'invoice_no' => $pendingInvoice->invoice_no,
                ] : null,
            ]
        ]);
    }

    public function uploadProof(Request $request, $invoiceId)
    {
        $request->validate([
            'proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $invoice = \DB::table('invoices')->where('id', $invoiceId)->first();

        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
        }

        $path = $request->file('proof')->store('payment-proofs', 'public');

        \DB::table('invoices')->where('id', $invoiceId)->update([
            'payment_proof'    => $path,
            'proof_uploaded_at' => now(),
            'status'           => 'pending_verification',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment proof uploaded successfully',
        ]);
    }

}