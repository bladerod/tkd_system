<?php
// app/Http/Controllers/AttendanceController.php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\ClassSession;
use App\Models\Classes;
use App\Models\User;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $fromDate = $request->get('from_date', now()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->format('Y-m-d'));
        $classId = $request->get('class_id');
        $instructorId = $request->get('instructor_id');
        $deviceId = $request->get('device_id');

        // Build query with relationships
        $query = AttendanceLog::with([
            'student', 
            'classSession.class', 
            'classSession.instructor',
            'device',
            'recordedBy'
        ]);

        // Apply date filters
        if ($fromDate && $toDate) {
            $query->whereBetween('checkin_time', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        // Apply other filters
        if ($classId) {
            $query->whereHas('classSession', function($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        if ($instructorId) {
            $query->whereHas('classSession', function($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            });
        }

        if ($deviceId) {
            $query->where('device_id', $deviceId);
        }

        // Get attendance logs with pagination
        $attendanceLogs = $query->orderBy('checkin_time', 'desc')->paginate(15);

        // Get filter data for dropdowns
        $classes = Classes::where('status', 'active')->get();
        $instructors = User::where('role', 'instructor')->get();
        $devices = Device::where('status', 'active')->get();

        // Get summary statistics
        $totalToday = AttendanceLog::whereDate('checkin_time', today())->count();
        $uniqueStudentsToday = AttendanceLog::whereDate('checkin_time', today())
            ->distinct('student_id')
            ->count('student_id');
        $activeClasses = ClassSession::whereDate('session_date', today())
            ->where('session_status', 'scheduled')
            ->count();

        return view('attendance', compact(
            'attendanceLogs', 
            'classes', 
            'instructors', 
            'devices',
            'fromDate',
            'toDate',
            'classId',
            'instructorId',
            'deviceId',
            'totalToday',
            'uniqueStudentsToday',
            'activeClasses'
        ));
    }

    public function manualOverride(Request $request)
    {
        // Handle manual override logic
        return redirect()->back()->with('success', 'Manual override applied');
    }

    public function addManual(Request $request)
    {
        // Handle manual attendance entry
        return redirect()->back()->with('success', 'Manual attendance added');
    }

    public function exportCsv(Request $request)
    {
        // Export logic for CSV
        $attendanceLogs = AttendanceLog::with(['student', 'classSession.class'])
            ->when($request->from_date, function($query) use ($request) {
                return $query->whereDate('checkin_time', '>=', $request->from_date);
            })
            ->when($request->to_date, function($query) use ($request) {
                return $query->whereDate('checkin_time', '<=', $request->to_date);
            })
            ->get();

        // CSV generation logic here
        return response()->stream(function() use ($attendanceLogs) {
            // CSV output
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendance.csv"',
        ]);
    }
}