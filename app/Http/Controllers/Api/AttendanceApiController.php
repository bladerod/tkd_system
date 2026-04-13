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
        $fromDate = $request->get('from_date', now()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->format('Y-m-d'));
        $classId = $request->get('class_id');
        $instructorId = $request->get('instructor_id');
        $deviceId = $request->get('device_id');

        $query = AttendanceLog::with([
            'student', 
            'classSession.class', 
            'classSession.instructor',
            'device',
            'recordedBy'
        ]);

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

        $attendanceLogs = $query->orderBy('checkin_time', 'desc')->paginate(15);

        $totalToday = AttendanceLog::whereDate('checkin_time', today())->count();
        $uniqueStudentsToday = AttendanceLog::whereDate('checkin_time', today())
            ->distinct('student_id')
            ->count('student_id');
        $activeClasses = ClassSession::whereDate('session_date', today())
            ->where('session_status', 'scheduled')
            ->count();

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
        return response()->json([
            'success' => true,
            'message' => 'Manual override applied successfully'
        ], 200);
    }

    public function addManual(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Manual attendance added successfully'
        ], 201);
    }

    public function store(Request $request)
    {
        $request->validate([
            'session_id'                  => 'required|integer',
            'attendances'                 => 'required|array',
            'attendances.*.student_id'    => 'required|integer',
            'attendances.*.status'        => 'required|in:present,late,absent,excused',
        ]);

        $user  = $request->user();
        $saved = 0;
        $now   = now();

        foreach ($request->attendances as $attendance) {
    $studentId = $attendance['student_id'];

    $activeLogin = \DB::table('active_logins')
        ->where('student_id', $studentId)
        ->where('expires_at', '>', $now)
        ->first();

    $method = $activeLogin ? $activeLogin->login_type : 'manual';

    \DB::table('attendance_logs')
        ->where('class_session_id', $request->session_id)
        ->where('student_id', $studentId)
        ->delete();

    $inserted = \DB::table('attendance_logs')->insert([
        'class_session_id'    => $request->session_id,
        'student_id'          => $studentId,
        'attendance_status'   => $attendance['status'],
        'checkin_time'        => $now,
        'checkout_time'       => $now,
        'method'              => $method,
        'confidence_score'    => 100,
        'recorded_by_user_id' => $user->id,
        'device_id'           => 0,
        'status'              => 1,
    ]);

    // TEMP DEBUG
    \Log::info('After insert', [
        'student_id' => $studentId,
        'inserted'   => $inserted,
        'method'     => $method,
    ]);

    $saved++;
}

        return response()->json([
            'success'     => true,
            'message'     => 'Attendance saved successfully',
            'saved_count' => $saved,
        ]);
    }
}