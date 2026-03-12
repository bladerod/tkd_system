<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AttendanceLog;
use App\Models\ClassSession;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function statistics(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id;

        $today = now()->format('Y-m-d');

        // Today's attendance
        $todayAttendance = AttendanceLog::whereDate('checkin_time', $today)
            ->when($branchId, function($q) use ($branchId) {
                $q->whereHas('student', function($sq) use ($branchId) {
                    $sq->where('branch_id', $branchId);
                });
            })
            ->count();

        // Today's classes
        $todayClasses = ClassSession::whereDate('session_date', $today)
            ->when($branchId, function($q) use ($branchId) {
                $q->whereHas('class', function($cq) use ($branchId) {
                    $cq->where('branch_id', $branchId);
                });
            })
            ->count();

        // Active students
        $activeStudents = Student::where('status', 'active')
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->count();

        // Upcoming exams
        $upcomingExams = DB::table('belt_exams')
            ->whereDate('exam_date', '>=', $today)
            ->when($branchId, function($q) use ($branchId) {
                $q->whereHas('class', function($cq) use ($branchId) {
                    $cq->where('branch_id', $branchId);
                });
            })
            ->count();

        // Recent announcements
        $recentAnnouncements = Announcement::active()
            ->when($branchId, function($q) use ($branchId) {
                $q->where('branch_id', $branchId)
                  ->orWhere('target_type', 'all');
            })
            ->latest('publish_date')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'today_attendance' => $todayAttendance,
                'today_classes' => $todayClasses,
                'active_students' => $activeStudents,
                'upcoming_exams' => $upcomingExams,
                'recent_announcements' => $recentAnnouncements
            ]
        ]);
    }

    /**
     * Get attendance trends
     */
    public function attendanceTrends(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id;
        $days = $request->get('days', 7);

        $startDate = now()->subDays($days - 1)->startOfDay();
        $endDate = now()->endOfDay();

        $trends = AttendanceLog::select(
                DB::raw('DATE(checkin_time) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(DISTINCT student_id) as unique_students')
            )
            ->whereBetween('checkin_time', [$startDate, $endDate])
            ->when($branchId, function($q) use ($branchId) {
                $q->whereHas('student', function($sq) use ($branchId) {
                    $sq->where('branch_id', $branchId);
                });
            })
            ->groupBy(DB::raw('DATE(checkin_time)'))
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $trends
        ]);
    }
}