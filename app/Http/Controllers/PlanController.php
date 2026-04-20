<?php

namespace App\Http\Controllers;

use App\Models\Plans;
use App\Models\Classes;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function sanitizeInput($value)
    {
        if ($value) {
            $value = trim($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $value;
    }

    public function index()
    {
        $plans = Plans::with('relatedClass')->where('active_flag', 1)->get();
        $classes = Classes::where('status', 'active')->get();
        return view('plans', compact('plans', 'classes'));
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'plan_name' => 'required|max:255',
            'description' => 'nullable|string',
            'session_type' => 'required|in:unlimited,limited',
            'sessions_count' => 'nullable|integer|min:1|max:100',
            'expiry_value' => 'nullable|integer|min:1|max:36',
            'expiry_unit' => 'nullable|in:weeks,months',
            'expiry_period_unlimited' => 'nullable|integer|min:1|max:24',
            'monthly_price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:weekly,monthly,quarterly,yearly',
            'status' => 'required|in:1,0',
        ]);

        try {
            // Determine exact values based on session type
            $isUnlimited = $validate['session_type'] === 'unlimited';
            
            Plans::create([
                'class_id' => $validate['class_id'],
                'plan_name' => $validate['plan_name'],
                'description' => $validate['description'],
                'session_type' => $validate['session_type'],
                // If unlimited, save 0 sessions. Otherwise, save the count.
                'sessions_count' => $isUnlimited ? 0 : ($validate['sessions_count'] ?? 0),
                // If unlimited, use the unlimited dropdown value. Otherwise, use the limited one.
                'expiry_value' => $isUnlimited ? ($validate['expiry_period_unlimited'] ?? 12) : ($validate['expiry_value'] ?? 0),
                // If unlimited, it's always months. Otherwise, use the selected unit.
                'expiry_unit' => $isUnlimited ? 'months' : ($validate['expiry_unit'] ?? 'months'),
                
                'sessions_per_week' => $isUnlimited ? 0 : ($validate['sessions_count'] ?? 0),
                'monthly_price' => $validate['monthly_price'],
                'unlimited_flag' => $isUnlimited ? 1 : 0,
                'billing_cycle' => $validate['billing_cycle'],
                'active_flag' => $validate['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('plans.index')->with('success', 'Plan added successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to add plan: ' . $th->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $plan = Plans::findOrFail($id);
            return response()->json(['success' => true, 'plan' => $plan]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Plan not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'plan_name' => 'required|max:150',
            'description' => 'nullable|string',
            'session_type' => 'required|in:unlimited,limited',
            'sessions_count' => 'nullable|integer|min:1|max:100',
            'expiry_value' => 'nullable|integer|min:1|max:36',
            'expiry_unit' => 'nullable|in:weeks,months',
            'expiry_period_unlimited' => 'nullable|integer|min:1|max:24',
            'monthly_price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:weekly,monthly,quarterly,yearly',
            'status' => 'required|in:1,0',
        ]);

        try {
            $plan = Plans::findOrFail($id);

            // Determine exact values based on session type
            $isUnlimited = $validate['session_type'] === 'unlimited';

            $plan->update([
                'class_id' => $validate['class_id'],
                'plan_name' => $validate['plan_name'],
                'description' => $validate['description'],
                'session_type' => $validate['session_type'],
                // If unlimited, save 0 sessions. Otherwise, save the count.
                'sessions_count' => $isUnlimited ? 0 : ($validate['sessions_count'] ?? 0),
                // If unlimited, use the unlimited dropdown value. Otherwise, use the limited one.
                'expiry_value' => $isUnlimited ? ($validate['expiry_period_unlimited'] ?? 12) : ($validate['expiry_value'] ?? 0),
                // If unlimited, it's always months. Otherwise, use the selected unit.
                'expiry_unit' => $isUnlimited ? 'months' : ($validate['expiry_unit'] ?? 'months'),
                
                'sessions_per_week' => $isUnlimited ? 0 : ($validate['sessions_count'] ?? 0),
                'monthly_price' => $validate['monthly_price'],
                'unlimited_flag' => $isUnlimited ? 1 : 0,
                'billing_cycle' => $validate['billing_cycle'],
                'active_flag' => $validate['status'],
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Plan updated successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Failed to update plan: ' . $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $plan = Plans::findOrFail($id);
            $plan->update(['active_flag' => 0]);

            return response()->json(['success' => true, 'message' => 'Plan deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Failed to delete plan: ' . $th->getMessage()], 500);
        }
    }
}
