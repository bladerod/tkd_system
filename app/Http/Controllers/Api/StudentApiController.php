<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentApiController extends Controller
{
    /**
     * Get all students with optional filters
     */
    public function index(Request $request)
    {
        $query = Student::with(['branch', 'primaryParent', 'currentBelt']);

        // Apply filters
        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->has('belt')) {
            $query->where('current_belt', $request->belt);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_code', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Get single student details
     */
    public function show($id)
    {
        $student = Student::with([
            'branch', 
            'parents.user', 
            'primaryParent.user',
            'classes' => function($q) {
                $q->wherePivot('status', 'active');
            },
            'attendanceLogs' => function($q) {
                $q->latest()->limit(10);
            },
            'subscriptions.plan',
            'evaluations.instructor'
        ])->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Get student attendance history
     */
    public function attendance($id, Request $request)
    {
        $student = Student::find($id);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        $attendance = $student->attendanceLogs()
            ->with(['classSession.class', 'classSession.instructor'])
            ->when($request->from_date, function($q, $from) {
                $q->whereDate('checkin_time', '>=', $from);
            })
            ->when($request->to_date, function($q, $to) {
                $q->whereDate('checkin_time', '<=', $to);
            })
            ->orderBy('checkin_time', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    /**
     * Get student belt progress
     */
    public function progress($id)
    {
        $student = Student::with([
            'beltExamResults' => function($q) {
                $q->with('exam')->latest();
            },
            'skillProgress.skill',
            'evaluations' => function($q) {
                $q->latest()->limit(5);
            }
        ])->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'current_belt' => $student->current_belt,
                'exam_history' => $student->beltExamResults,
                'skill_progress' => $student->skillProgress,
                'recent_evaluations' => $student->evaluations
            ]
        ]);
    }
}