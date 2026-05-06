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
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $classId = $request->input('class_id');
        $instructorId = $request->input('instructor_id');
        $deviceId = $request->input('device_id');
        $branches = Branch::where('status', 'active')->get();

        // FIX 1: Use query() to build the query before executing it
        $query = VwAttendanceLog::query();

        $classes = Classes::all();
        $instructors = Instructor::all();

        // Apply date filters
        if ($fromDate && $toDate) {
            $query->whereBetween('checkin_time', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        // FIX 2: VwAttendanceLog is a View without foreign keys. 
        // We find the name associated with the ID and filter the view by that name.
        if ($classId) {
            $targetClass = Classes::find($classId);
            if ($targetClass) {
                $query->where('class_name', $targetClass->class_name);
            }
        }

        if ($instructorId) {
            $instructor = Instructor::find($instructorId);
            if ($instructor) {
                $instructorName = $instructor->fname . ' ' . $instructor->lname;
                $query->where('instructor_name', $instructorName);
            }
        }

        if ($deviceId) {
            $query->where('device_id', $deviceId);
        }

        // FIX 3: Execute the filtered query (Do NOT use ::all() here)
        $attendanceLogs = $query->get();

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
        $query = VwAttendanceLog::query();

        // Apply filters properly to the CSV export as well
        if ($request->from_date && $request->to_date) {
            $query->whereBetween('checkin_time', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
        }

        if ($request->class_id) {
            $targetClass = Classes::find($request->class_id);
            if ($targetClass) {
                $query->where('class_name', $targetClass->class_name);
            }
        }

        if ($request->instructor_id) {
            $instructor = Instructor::find($request->instructor_id);
            if ($instructor) {
                $instructorName = $instructor->fname . ' ' . $instructor->lname;
                $query->where('instructor_name', $instructorName);
            }
        }

        if ($request->device_id) {
            $query->where('device_id', $request->device_id);
        }

        $attendanceLogs = $query->get();

        $filename = 'attendance_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($attendanceLogs) {
            $handle = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");

            // Cleaned up headers
            fputcsv($handle, [
                'ID',
                'Student Name',
                'Student Code',
                'Class',
                'Instructor',
                'Branch',
                'Date',
                'Check-in Time',
                'Check-out Time',
                'Method',
                'Status',
                'Confidence Score'
            ]);

            foreach ($attendanceLogs as $log) {
                $attendanceDate = 'N/A';
                $checkinTime = 'N/A';
                $checkoutTime = 'N/A';

                // Safe parsing for Check-in
                if (!empty($log->checkin_time)) {
                    $checkinStr = (string)$log->checkin_time;
                    // Ignore null, 0000, or negative years
                    if (strpos($checkinStr, '0000') === false && strpos($checkinStr, '-0001') === false) {
                        try {
                            $date = \Carbon\Carbon::parse($checkinStr);
                            if ($date->year > 1970) {
                                $attendanceDate = $date->format('m/d/Y'); // E.g., Mar 10, 2026
                                $checkinTime = $date->format('h:i A');     // E.g., 08:00 AM
                            }
                        } catch (\Exception $e) {}
                    }
                }

                // Safe parsing for Check-out
                if (!empty($log->checkout_time)) {
                    $checkoutStr = (string)$log->checkout_time;
                    // Ignore null, 0000, or negative years
                    if (strpos($checkoutStr, '0000') === false && strpos($checkoutStr, '-0001') === false) {
                        try {
                            $date = \Carbon\Carbon::parse($checkoutStr);
                            if ($date->year > 1970) {
                                $checkoutTime = $date->format('h:i A'); // E.g., 09:30 AM
                            }
                        } catch (\Exception $e) {}
                    }
                }

                fputcsv($handle, [
                    $log->id,
                    $log->student_name ?? 'N/A',
                    $log->student_code ?? 'N/A',
                    $log->class_name ?? 'N/A',
                    $log->instructor_name ?? 'N/A',
                    $log->branch ?? 'N/A',
                    $attendanceDate,
                    $checkinTime,
                    $checkoutTime,
                    $log->method ?? 'N/A',
                    $log->attendance_status ?? 'N/A',
                    $log->confidence_score ?? 'N/A'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}