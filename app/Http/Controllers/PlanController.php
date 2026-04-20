<?php

namespace App\Http\Controllers;

use App\Models\Plans;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function sanitizeInput($value)
    {
        if($value){
            $value = trim($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return $value; 
    }

    public function index()
    {
        $plans = Plans::all()->where('active_flag',1);
        
        return view('plans', compact('plans'));
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'plan_name' => 'required|max:255',
            'description' => 'required',
            'monthly_price' => 'required',
            'billing_cycle' => 'required',
            'status' => 'required|in:1,0',
        ]);
        
        try {
            Plans::create($validate);
        } catch (\Throwable $th) {
            return redirect()->with('error', 'Failed to add plan') . $th->getMessage();
        }
        return redirect()->route('plans.index')->with('success', 'Adding plans successfully');
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
            'plan_name' => 'required|max:150',
            'description' => 'nullable|string',
            'monthly_price' => 'nullable|numeric|min:0',
            'billing_cycle' => 'nullable|in:weekly,monthly,quarterly,yearly',
            'status' => 'required|in:1,0',
        ]);
        
        try {
            $plan = Plans::findOrFail($id);
            
            $plan->update([
                'plan_name' => $validate['plan_name'],
                'description' => $validate['description'] ?? null,
                'monthly_price' => $validate['monthly_price'] ?? 0,
                'billing_cycle' => $validate['billing_cycle'] ?? 'monthly',
                'active_flag' => $validate['status'],
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
