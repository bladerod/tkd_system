<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use App\Models\Student;
use Illuminate\Http\Request;

class ParentApiController extends Controller
{
    /**
     * Get parent profile and children
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        $parent = Parents::with(['user', 'students' => function($q) {
            $q->with(['branch', 'currentBelt', 'classes']);
        }])->where('user_id', $user->id)->first();

        if (!$parent) {
            return response()->json([
                'success' => false,
                'message' => 'Parent profile not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $parent
        ]);
    }

    /**
     * Get child's details
     */
    public function childDetails($childId, Request $request)
    {
        $user = $request->user();
        
        $parent = Parents::where('user_id', $user->id)->first();
        
        if (!$parent) {
            return response()->json([
                'success' => false,
                'message' => 'Parent profile not found'
            ], 404);
        }

        $student = Student::whereHas('parents', function($q) use ($parent) {
                $q->where('id', $parent->id);
            })
            ->with([
                'branch',
                'classes' => function($q) {
                    $q->with('primaryInstructor.user', 'schedules')
                      ->wherePivot('status', 'active');
                },
                'attendanceLogs' => function($q) {
                    $q->with('classSession.class')
                      ->latest()
                      ->limit(20);
                },
                'evaluations' => function($q) {
                    $q->with('instructor.user')
                      ->latest()
                      ->limit(5);
                },
                'beltExamResults.exam'
            ])
            ->find($childId);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found or not associated with this parent'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Get child's attendance
     */
    public function childAttendance($childId, Request $request)
    {
        $user = $request->user();
        
        $parent = Parents::where('user_id', $user->id)->first();
        
        if (!$parent) {
            return response()->json([
                'success' => false,
                'message' => 'Parent profile not found'
            ], 404);
        }

        $student = Student::whereHas('parents', function($q) use ($parent) {
                $q->where('id', $parent->id);
            })->find($childId);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        $attendance = $student->attendanceLogs()
            ->with('classSession.class')
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
}