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
            'students' => function($q) {
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
            ->when($request->from_date, function($q, $from) {
                $q->whereDate('session_date', '>=', $from);
            })
            ->when($request->to_date, function($q, $to) {
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

    $classes = Classes::with(['branch', 'schedules', 'students' => function($q) {
            $q->where('status', 'active');
        }])
        ->where(function($q) use ($instructor) {
            $q->where('primary_instructor_id', $instructor->id)
              ->orWhere('assistant_instructor_id', $instructor->id);
        })
        ->where('status', 'active')
        ->get();

    return response()->json([
        'success' => true,
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

    $students = $classStudents->map(function($cs) {
    $student = \App\Models\Student::find($cs->student_id);

    if (!$student) return null;

    return [
        'id' => $student->id,
        'name' => $student->student_name ?? 'Unknown',
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
}