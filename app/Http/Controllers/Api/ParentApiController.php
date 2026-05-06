<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentApiController extends Controller
{
    /**
     * Get parent profile and children
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        $parent = Parents::with([
            'user',
            'students' => function ($q) {
                $q->with(['branch', 'currentBelt', 'classes']);
            }
        ])->where('user_id', $user->id)->first();

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

        $student = Student::whereHas('parents', function ($q) use ($parent) {
            $q->where('id', $parent->id);
        })
            ->with([
                'branch',
                'classes' => function ($q) {
                    $q->with('primaryInstructor.user', 'schedules')
                        ->wherePivot('status', 'active');
                },
                'attendanceLogs' => function ($q) {
                    $q->with('classSession.class')
                        ->latest()
                        ->limit(20);
                },
                'evaluations' => function ($q) {
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

    public function myProfile(Request $request)
    {
        $user = $request->user();

        // Get parent record
        $parent = \DB::table('parents')
            ->where('user_id', $user->id)
            ->first();

        if (!$parent) {
            return response()->json([
                'success' => false,
                'message' => 'Parent profile not found'
            ], 404);
        }

        // Get children via parent_students table
        $children = \DB::table('parent_students')
            ->where('parent_id', $parent->id)
            ->join('students', 'parent_students.student_id', '=', 'students.id')
            ->leftJoin('belt_levels', 'students.current_belt', '=', 'belt_levels.id')
            ->select(
                'students.id',
                'students.first_name',
                'students.last_name',
                'students.status',
                'belt_levels.name as belt_name'
            )
            ->get();

        $childrenData = $children->map(function ($child) {
            $classStudent = \DB::table('class_students')
                ->where('student_id', $child->id)
                ->where('status', 'active')
                ->first();

            $nextClass = 'No class scheduled';
            if ($classStudent) {
                $schedule = \DB::table('class_schedules')
                    ->where('class_id', $classStudent->class_id)
                    ->first();
                if ($schedule) {
                    $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time);
                    $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time);
                    $nextClass = ucfirst($schedule->day_of_week) . ' ' .
                        $startTime->format('g:i A') . ' - ' .
                        $endTime->format('g:i A');
                }
            }

            // Get plan name
            $subscription = \DB::table('student_subscriptions')
                ->where('student_id', $child->id)
                ->where('status', 'active')
                ->first();

            $planName = null;
            if ($subscription) {
                $plan = \DB::table('plans')->where('id', $subscription->plan_id)->first();
                $planName = $plan?->plan_name ?? null;
            }

            return [
                'id' => $child->id,
                'name' => trim($child->first_name . ' ' . $child->last_name),
                'belt' => $child->belt_name ?? 'No Belt',
                'status' => $child->status ?? 'active',
                'next_class' => $nextClass,
                'balance' => '₱0',
                'photo_url' => $child->photo_url ?? null,
                'plan_name' => $planName ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'name' => trim($user->fname . ' ' . $user->lname),
                'email' => $user->email ?? '',
                'mobile' => $user->mobile ?? '',
                'children' => $childrenData,
                'alerts' => [],
            ]
        ]);
    }

    public function childProfile(Request $request, $childId)
    {
        $user = $request->user();
        $parent = \DB::table('parents')->where('user_id', $user->id)->first();

        if (!$parent) {
            return response()->json(['success' => false, 'message' => 'Parent not found'], 404);
        }

        // Verify child belongs to parent
        $linked = \DB::table('parent_students')
            ->where('parent_id', $parent->id)
            ->where('student_id', $childId)
            ->exists();

        if (!$linked) {
            return response()->json(['success' => false, 'message' => 'Child not found'], 404);
        }

        $student = \DB::table('students')
            ->where('id', $childId)
            ->first();

        $belt = \DB::table('belt_levels')->where('id', $student->current_belt)->first();

        $classStudent = \DB::table('class_students')
            ->where('student_id', $childId)
            ->where('status', 'active')
            ->first();

        $instructor = 'TBA';
        $nextClass = 'No class scheduled';

        if ($classStudent) {
            $class = \DB::table('classes')->where('id', $classStudent->class_id)->first();
            if ($class) {
                $instructorRecord = \DB::table('instructors')->where('id', $class->primary_instructor_id)->first();
                if ($instructorRecord) {
                    $instructorUser = \DB::table('users')->where('id', $instructorRecord->user_id)->first();
                    $instructor = $instructorUser ? trim($instructorUser->fname . ' ' . $instructorUser->lname) : 'TBA';
                }
                $schedule = \DB::table('class_schedules')->where('class_id', $class->id)->first();
                if ($schedule) {
                    $nextClass = ucfirst($schedule->day_of_week) . ' ' . $schedule->start_time;
                }
            }
        }

        // Attendance stats
        $totalClasses = \DB::table('attendance_logs')->where('student_id', $childId)->count();
        $attended = \DB::table('attendance_logs')
            ->where('student_id', $childId)
            ->whereIn('attendance_status', ['present', 'late'])
            ->count();

        $attendancePercentage = $totalClasses > 0 ? round(($attended / $totalClasses) * 100, 1) : 0;

        $joinDate = $student->join_date ? \Carbon\Carbon::parse($student->join_date) : null;
        $monthsTraining = $joinDate ? $joinDate->diffInMonths(now()) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $student->id,
                'name' => trim($student->first_name . ' ' . $student->last_name),
                'age' => $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->age : 0,
                'belt' => $belt->name ?? 'No Belt',
                'instructor' => $instructor,
                'next_class' => $nextClass,
                'member_since' => $joinDate ? $joinDate->format('F Y') : 'N/A',
                'next_belt_test' => 'TBA',
                'classes_per_week' => 2,
                'attendance_percentage' => $attendancePercentage,
                'months_training' => $monthsTraining,
                'awards_count' => 0,
            ]
        ]);
    }

    public function childAttendance($childId)
    {
        try {
            $logs = DB::table('attendance_logs as al')
                ->join('class_sessions as cs', 'al.class_session_id', '=', 'cs.id')
                ->join('classes as c', 'cs.class_id', '=', 'c.id')
                ->where('al.student_id', $childId)
                ->orderBy('al.checkin_time', 'desc')
                ->select(
                    'al.id',
                    'al.attendance_status',
                    'al.checkin_time',
                    'al.method',
                    'c.class_name'
                )
                ->get();

            $records = $logs->map(function ($log) {
                $date = \Carbon\Carbon::parse($log->checkin_time);
                $status = ucfirst($log->attendance_status);
                return [
                    'day' => $date->format('d'),
                    'month' => $date->format('M'),
                    'time' => $date->format('g:i A'),
                    'status' => $status,
                    'method' => ucfirst($log->method ?? 'Manual'),
                ];
            });

            $summary = [
                'present' => $logs->where('attendance_status', 'present')->count(),
                'absent' => $logs->where('attendance_status', 'absent')->count(),
                'late' => $logs->where('attendance_status', 'late')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'records' => $records->values(),
                    'summary' => $summary,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}