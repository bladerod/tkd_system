<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'type' =>'required|in:fixed,percentage',
            'value' => 'required',
            'applicable_to' => 'required|in:all,monthly fee,enrollment',
            'valid_from' => 'required',
            'valid_to' => 'required',
            'status' => 'required|in:1,0',
        ]);

        Discount::create($validate);
        return redirect()->back()->with('success', 'the discount has been added');
    }
}
