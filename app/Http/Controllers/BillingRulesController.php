<?php

namespace App\Http\Controllers;

use App\Models\BillingRules;
use Illuminate\Http\Request;

class BillingRulesController extends Controller
{
    public function update(Request $request)
    {   
        $validated = $request->validate([
            'monthlyFee' => 'required|numeric',
            'enrollmentFees' => 'nullable|numeric',
            'uniformFees' => 'nullable|numeric',
            'beltPromotionFees' => 'nullable|numeric',
            'competitionFees' => 'nullable|numeric',
            'billingCycle' => 'required|in:Monthly,Quarterly',
            'dueDateRule' => 'required|in:1st of Month,5th of Month,10th of Month,15th of Month,Custom Day',
            'gracePeriod' => 'required|integer',
            'lateFeesType' => 'required|in:Fixed,Percentage',
            'lateFeeAmount' => ['required', 'string', 'regex:/^\d+(\.\d{1,2})?%?$/'],
            'allowPartialPayment' => 'required|in:Yes,No',
            'autoMarkOverdue' => 'required|in:Yes,No',
            'autoGenerateMonthlyInvoice' => 'required|in:Yes,No',
        ]);

        $billingRule = BillingRules::first() ?: new BillingRules;

        $billingRule->fill([
            'monthly_fee'              => $validated['monthlyFee'],
            'enrollment_fees'           => $validated['enrollmentFees'],
            'uniform_fees'              => $validated['uniformFees'], 
            'belt_promotion_fees'       => $validated['beltPromotionFees'],
            'competition_fees'          => $validated['competitionFees'],
            'billing_cycle'             => $validated['billingCycle'],
            'due_date_rule'             => $validated['dueDateRule'],
            'grace_period'              => $validated['gracePeriod'],
            'late_fees_type'            => $validated['lateFeesType'],
            'late_fee_amount'          => $validated['lateFeeAmount'],
            
            'allow_partial_payment'     => $validated['allowPartialPayment'] === 'Yes' ? 1 : 0,
            'auto_mark_overdue'         => $validated['autoMarkOverdue'] === 'Yes' ? 1 : 0,
            'auto_generate_monthly_invoice' => $validated['autoGenerateMonthlyInvoice'] === 'Yes' ? 1 : 0,
        ]);

        $billingRule->save();

        return redirect()->back()->with('success', 'Billing rules updated successfully!');
    }

    public function index()
    {
        $rules = BillingRules::first();

        return view('billingrules',compact('rules'));
    }

}
