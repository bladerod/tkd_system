<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    public function index()
    {   
        $discounts = Discount::all();
        return view('discounts', compact('discounts'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100',
                'type' => 'required|in:percent,fixed',
                'value' => 'required|numeric|min:0',
                'applicable_to' => 'required|in:all,monthly_fee,enrollment',
                'valid_from' => 'required|date',
                'valid_to' => 'required|date|after_or_equal:valid_from',
                'status' => 'required|in:1,0',
            ]);

            // Sanitize input
            $validated['name'] = strip_tags(trim($validated['name']));
            $validated['type'] = strip_tags(trim($validated['type']));
            $validated['applicable_to'] = strip_tags(trim($validated['applicable_to']));

            Discount::create($validated);
            
            Log::info('Discount created successfully', ['name' => $validated['name']]);
            
            return redirect()->back()->with('success', 'Discount has been added successfully!');
            
        } catch (\Exception $e) {
            Log::error('Failed to create discount', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to add discount. Please try again.');
        }
    }

    /**
     * Display the specified discount for editing.
     */
    public function show($id)
    {
        try {
            $id = (int) $id;
            $discount = Discount::findOrFail($id);
            
            // Sanitize output data before sending as JSON
            $discountData = [
                'id' => $discount->id,
                'name' => strip_tags($discount->name),
                'type' => strip_tags($discount->type),
                'value' => (float) $discount->value,
                'applicable_to' => strip_tags($discount->applicable_to),
                'valid_from' => $discount->valid_from,
                'valid_to' => $discount->valid_to,
                'status' => (int) $discount->status,
            ];
            
            return response()->json($discountData);
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch discount', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Discount not found'], 404);
        }
    }

    /**
     * Update the specified discount.
     */
    public function update(Request $request, $id)
    {
        try {
            $id = (int) $id;
            $discount = Discount::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:100',
                'type' => 'required|in:percent,fixed',
                'value' => 'required|numeric|min:0',
                'applicable_to' => 'required|in:all,monthly_fee,enrollment',
                'valid_from' => 'required|date',
                'valid_to' => 'required|date|after_or_equal:valid_from',
                'status' => 'required|in:1,0',
            ]);
            
            // Sanitize input
            $validated['name'] = strip_tags(trim($validated['name']));
            $validated['type'] = strip_tags(trim($validated['type']));
            $validated['applicable_to'] = strip_tags(trim($validated['applicable_to']));
            
            $discount->update($validated);
            
            Log::info('Discount updated successfully', ['id' => $id, 'name' => $validated['name']]);
            
            return redirect()->route('discounts.index')->with('success', 'Discount updated successfully!');
            
        } catch (\Exception $e) {
            Log::error('Failed to update discount', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update discount. Please try again.');
        }
    }

    /**
     * Remove the specified discount.
     */
    public function destroy($id)
    {
        try {
            $id = (int) $id;
            $discount = Discount::findOrFail($id);
            $discountName = $discount->name;
            
            // Delete the discount
            $discount->delete();
            
            Log::info('Discount deleted successfully', ['id' => $id, 'name' => $discountName]);
            
            return redirect()->route('discounts.index')->with('success', 'Discount "' . $discountName . '" has been deleted successfully!');
            
        } catch (\Exception $e) {
            Log::error('Failed to delete discount', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('discounts.index')->with('error', 'Failed to delete discount. Please try again.');
        }
    }
}