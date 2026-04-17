<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\ClassSession;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;

class ClassApiController extends Controller
{
    /**
     * Get all classes
     */
    public function index(Request $request)
    {
        $query = Classes::with(['branch', 'primaryInstructor.user', 'assistantInstructor.user']);

        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $classes = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }

    /**
     * Get class details with schedule and students
     */
    public function show($id)
    {
        $class = Classes::with([
            'branch',
            'primaryInstructor.user',
            'assistantInstructor.user',
            'schedules',
            'students' => function ($q) {
                $q->wherePivot('status', 'active')
                    ->with('primaryParent.user');
            }
        ])->find($id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $class
        ]);
    }

    /**
     * Get class sessions
     */
    public function sessions($id, Request $request)
    {
        $class = Classes::find($id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        $sessions = ClassSession::where('class_id', $id)
            ->with('instructor.user')
            ->when($request->from_date, function ($q, $from) {
                $q->whereDate('session_date', '>=', $from);
            })
            ->when($request->to_date, function ($q, $to) {
                $q->whereDate('session_date', '<=', $to);
            })
            ->orderBy('session_date', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    /**
     * Get today's sessions for a class
     */
    public function todaySessions($id)
    {
        $sessions = ClassSession::where('class_id', $id)
            ->whereDate('session_date', today())
            ->with('instructor.user')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    /**
     * Get classes of the authenticated instructor
     */

    public function myClasses(Request $request)
    {
        $user = $request->user();
        $instructor = $user->instructor;

        if (!$instructor) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor profile not found'
            ], 404);
        }

        $today = strtolower(now()->format('l')); // monday to sunday

        $classes = Classes::with([
            'branch',
            'schedules' => function ($q) use ($today) {
                $q->where('day_of_week', $today); // today's schedule lang
            },
            'students' => function ($q) {
                $q->where('status', 'active');
            }
        ])
            ->where(function ($q) use ($instructor) {
                $q->where('primary_instructor_id', $instructor->id)
                    ->orWhere('assistant_instructor_id', $instructor->id);
            })
            ->where('status', 'active')
            ->whereHas('schedules', function ($q) use ($today) {
                $q->where('day_of_week', $today); // classes na may sched ngaun
            })
            ->get();

        return response()->json([
            'success' => true,
            'today' => $today,
            'data' => $classes
        ]);
    }

    public function classStudents($id)
    {
        $class = Classes::find($id);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        $classStudents = \App\Models\ClassStudent::where('class_id', $id)
            ->where('status', 'active')
            ->get();

        $students = $classStudents->map(function ($cs) {
            $student = \App\Models\Student::find($cs->student_id);

            if (!$student)
                return null;

            return [
                'id' => $student->id,
                'name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: 'Unknown',
                'belt' => $student->current_belt ?? 'No Belt',
                'status' => $cs->status,
            ];
        })->filter()->values();

        return response()->json([
            'success' => true,
            'data' => [
                'class' => [
                    'id' => $class->id,
                    'name' => $class->class_name,
                    'level' => $class->level,
                ],
                'students' => $students,
            ]
        ]);
    }

    public function startSession(Request $request, $id)
    {
        $user = $request->user();
        $instructor = $user->instructor;

        if (!$instructor) {
            return response()->json(['success' => false, 'message' => 'Instructor not found'], 404);
        }

        // Find or create today's session
        $session = ClassSession::firstOrCreate(
            [
                'class_id' => $id,
                'session_date' => today()->toDateString(),
            ],
            [
                'instructor_id' => $instructor->id,
                'start_time' => now()->format('H:i:s'),
                'end_time' => now()->addHours(1)->format('H:i:s'),
                'session_status' => 'ongoing',
            ]
        );

        // Update to ongoing if existing
        if (!$session->wasRecentlyCreated) {
            $session->update(['session_status' => 'ongoing']);
        }

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
        ]);
    }

    public function attendanceStats(Request $request)
    {
        $user = $request->user();
        $instructor = $user->instructor;

        if (!$instructor) {
            return response()->json(['success' => false, 'message' => 'Instructor not found'], 404);
        }

        // Get today's class IDs ng instructor
        $classIds = Classes::where(function ($q) use ($instructor) {
            $q->where('primary_instructor_id', $instructor->id)
                ->orWhere('assistant_instructor_id', $instructor->id);
        })->pluck('id');

        // Get today's session IDs
        $sessionIds = \DB::table('class_sessions')
            ->whereIn('class_id', $classIds)
            ->whereDate('session_date', today())
            ->pluck('id');

        // Count per status
        $present = \DB::table('attendance_logs')
            ->whereIn('class_session_id', $sessionIds)
            ->where('attendance_status', 'present')
            ->count();

        $absent = \DB::table('attendance_logs')
            ->whereIn('class_session_id', $sessionIds)
            ->where('attendance_status', 'absent')
            ->count();

        $late = \DB::table('attendance_logs')
            ->whereIn('class_session_id', $sessionIds)
            ->where('attendance_status', 'late')
            ->count();

        $excused = \DB::table('attendance_logs')
            ->whereIn('class_session_id', $sessionIds)
            ->where('attendance_status', 'excused')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
            ]
        ]);
    }
}