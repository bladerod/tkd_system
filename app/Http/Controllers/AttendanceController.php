<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Branch;
use App\Models\Classes;
use App\Models\ClassSession;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\VwAttendanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $branches = Branch::where('status','active')->get();

        // Build query with relationships
        $query = VwAttendanceLog::all();

        $classes = Classes::all();
        $instructors = Instructor::all();

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

        // Get attendance logs
        $attendanceLogs = VwAttendanceLog::all();

        // Get summary statistics
        $totalToday = AttendanceLog::whereDate('checkin_time', today())->count();
        $uniqueStudentsToday = AttendanceLog::whereDate('checkin_time', today())
            ->distinct('student_id')
            ->count('student_id');
        $activeClasses = Classes::where('status', 'active')->count();

        // Get students and class sessions for dropdowns
        $students = Student::where('status', 'active')->get();
        $classSessions = ClassSession::with('class')->where('session_date', '>=', now()->subDays(30))->orderBy('session_date', 'desc')->get();

        return view('attendance', compact(
            'attendanceLogs', 
            'branches',
            'classes', 
            'instructors',
            'fromDate',
            'toDate',
            'classId',
            'instructorId',
            'deviceId',
            'totalToday',
            'uniqueStudentsToday',
            'activeClasses',
            'students',
            'classSessions'
        ));
    }

    public function manualOverride(Request $request)
    {
        return redirect()->back()->with('success', 'Manual override applied');
    }

    public function addManual(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_session_id' => 'required|exists:class_sessions,id',
            'checkin_time' => 'required|date',
            'attendance_status' => 'required|in:present,late,absent,excused',
        ]);

        try {
            // Get the class session to determine checkout time
            $classSession = ClassSession::findOrFail($validated['class_session_id']);
            
            // Default checkout time is 1 hour after checkin if not specified
            $checkinTime = \Carbon\Carbon::parse($validated['checkin_time']);
            $checkoutTime = clone $checkinTime;
            $checkoutTime->addHour(); // Default 1 hour session
            
            // Determine if student is late (checkin after class start time)
            $attendanceStatus = $validated['attendance_status'];
            
            // Create attendance log
            AttendanceLog::create([
                'student_id' => $validated['student_id'],
                'class_session_id' => $validated['class_session_id'],
                'checkin_time' => $checkinTime,
                'checkout_time' => $checkoutTime,
                'method' => 'manual',
                'confidence_score' => '100',
                'recorded_by_user_id' => Auth::id(),
                'device_id' => 0,
                'status' => 1,
                'attendance_status' => $attendanceStatus,
            ]);

            return redirect()->route('attendance.index')->with('success', 'Manual attendance added successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add attendance: ' . $e->getMessage());
        }
    }

    public function exportCsv(Request $request)
    {
        $attendanceLogs = AttendanceLog::with(['student', 'classSession.class'])
            ->when($request->from_date, function($query) use ($request) {
                return $query->whereDate('checkin_time', '>=', $request->from_date);
            })
            ->when($request->to_date, function($query) use ($request) {
                return $query->whereDate('checkin_time', '<=', $request->to_date);
            })
            ->get();

        $filename = 'attendance_' . date('Y-m-d_His') . '.csv';
        
        $handle = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($handle, ['ID', 'Student Name', 'Student Code', 'Class', 'Check-in Time', 'Check-out Time', 'Method', 'Status']);
        
        foreach ($attendanceLogs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->student_name ?? ($log->student->first_name . ' ' . $log->student->last_name ?? 'N/A'),
                $log->student_code ?? ($log->student->student_code ?? 'N/A'),
                $log->class_name ?? ($log->classSession->class->class_name ?? 'N/A'),
                $log->checkin_time,
                $log->checkout_time,
                $log->method,
                $log->attendance_status,
            ]);
        }
        
        fclose($handle);
        
        return response()->stream(function() use ($attendanceLogs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Student Name', 'Student Code', 'Class', 'Check-in Time', 'Check-out Time', 'Method', 'Status']);
            
            foreach ($attendanceLogs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->student_name ?? ($log->student->first_name . ' ' . $log->student->last_name ?? 'N/A'),
                    $log->student_code ?? ($log->student->student_code ?? 'N/A'),
                    $log->class_name ?? ($log->classSession->class->class_name ?? 'N/A'),
                    $log->checkin_time,
                    $log->checkout_time,
                    $log->method,
                    $log->attendance_status,
                ]);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}