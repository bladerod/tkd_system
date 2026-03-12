<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use App\Models\AttendanceLog;
use App\Models\ClassSession;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
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

        // Get summary statistics
        $totalToday = AttendanceLog::whereDate('checkin_time', today())->count();
        $uniqueStudentsToday = AttendanceLog::whereDate('checkin_time', today())
            ->distinct('student_id')
            ->count('student_id');
        $activeClasses = ClassSession::whereDate('session_date', today())
            ->where('session_status', 'scheduled')
            ->count();

        // 3. Return JSON instead of a Blade view
        return response()->json([
            'success' => true,
            'data' => $attendanceLogs,
            'statistics' => [
                'total_today' => $totalToday,
                'unique_students_today' => $uniqueStudentsToday,
                'active_classes' => $activeClasses
            ]
        ], 200);
    }

    public function manualOverride(Request $request)
    {
        // Add your manual override logic here...

        // Return JSON instead of redirect()->back()
        return response()->json([
            'success' => true,
            'message' => 'Manual override applied successfully'
        ], 200);
    }

    public function addManual(Request $request)
    {
        // Add your logic to save the new attendance record here...
        // Example: AttendanceLog::create($request->all());

        // Return JSON instead of redirect()->back()
        return response()->json([
            'success' => true,
            'message' => 'Manual attendance added successfully'
        ], 201);
    }
}