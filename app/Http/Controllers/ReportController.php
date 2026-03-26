<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
public function attendance(Request $request)
{
    $search = $request->search;

    $attendance = \DB::table('vw_attendance_reports')
        ->when($search, function ($query) use ($search) {
            $query->where('student', 'like', "%$search%");
        })
        ->get();

    return view('reports.attendance', compact('attendance', 'search'));
}
    public function revenue(Request $request)
    {
        $search = $request->search;

        $revenue = \DB::table('vw_revenue_reports')
            ->when($search, function ($query) use ($search) {
                $query->where('student', 'like', "%$search%");
            })
            ->get();

        return view('reports.revenue', compact('revenue', 'search'));
    }

    public function billing(Request $request)
    {
        $search = $request->search;

        $billing = \DB::table('vw_billing_summary')
            ->when($search, function ($query) use ($search) {
                $query->where('parent', 'like', "%$search%");
            })
            ->get();

        return view('reports.billing', compact('billing', 'search'));
    }

    public function instructor(Request $request)
    {
        $search = $request->search;

        $instructors = \DB::table('vw_instructor_load')
            ->when($search, function ($query) use ($search) {
                $query->where('instructor', 'like', "%$search%");
            })
            ->get();

        return view('reports.instructor', compact('instructors', 'search'));
    }
}
