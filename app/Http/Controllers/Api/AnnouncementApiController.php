<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnnouncementApiController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $user = $request->user();

        $announcements = \DB::table('announcements')
            ->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('expire_date')
                    ->orWhere('expire_date', '>=', $now);
            })
            ->where('publish_date', '<=', $now)
            ->where(function ($q) use ($user) {
                // General announcements — all, parents, students
                $q->whereIn('target_type', ['all', 'parents', 'students'])
                    // Specific — i-check kung nandoon yung user sa recipients
                    ->orWhere(function ($q2) use ($user) {
                    $q2->whereIn('target_type', ['specific_students', 'specific_parents', 'class'])
                        ->whereExists(function ($q3) use ($user) {
                            $q3->select(\DB::raw(1))
                                ->from('announcement_recipients')
                                ->whereColumn('announcement_recipients.announcement_id', 'announcements.id')
                                ->where('announcement_recipients.user_id', $user->id);

                        });
                });
            })
            ->whereNotExists(function ($q) use ($user) {
                $q->select(\DB::raw(1))
                    ->from('announcement_reads')
                    ->whereColumn('announcement_reads.announcement_id', 'announcements.id')
                    ->where('announcement_reads.user_id', $user->id)
                    ->where('announcement_reads.is_dismissed', 1);
            })

            ->orderBy('publish_date', 'desc')
            ->get()
            ->map(function ($a) use ($user) {
                $isRead = \DB::table('announcement_reads')
                    ->where('announcement_id', $a->id)
                    ->where('user_id', $user->id)
                    ->exists();

                return [
                    'id' => $a->id,
                    'title' => $a->title,
                    'message' => $a->message,
                    'publish_date' => $a->publish_date
                        ? \Carbon\Carbon::parse($a->publish_date)->format('M j, Y')
                        : '',
                    'sent_at' => $a->publish_date
                        ? \Carbon\Carbon::parse($a->publish_date)->format('g:i A')
                        : '',
                    'channel' => $a->channel,
                    'target_type' => $a->target_type,
                    'location' => $a->location,
                    'event_date' => $a->event_date,
                    'event_time' => $a->event_time,
                    'fee' => $a->fee,
                    'is_read' => $isRead,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $announcements,
        ]);
    }

    public function markOneRead(Request $request, $id)
    {
        $user = $request->user();

        \DB::table('announcement_reads')->insertOrIgnore([
            'announcement_id' => $id,
            'user_id' => $user->id,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $announcement = \DB::table('announcements')
            ->where('id', $id)
            ->where('status', 'active')
            ->first();

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => 'Announcement not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $announcement,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        $user = $request->user();

        $id = \DB::table('announcements')->insertGetId([
            'title' => $request->title,
            'message' => $request->message,
            'publish_date' => now(),
            'expire_date' => now()->addDays(30),
            'status' => 'active',
            'channel' => $request->channel ?? 'App',
            'target_type' => $request->target_type ?? 'all',
            'location' => $request->location,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'fee' => $request->fee,
            'created_by' => $user->id,
        ]);

        // Kung specific — i-insert sa announcement_recipients
        if (in_array($request->target_type, ['specific_students', 'specific_parents'])) {
            $userIds = $request->input('user_ids', []);

            // Kung string — i-convert to array
            if (is_string($userIds)) {
                $userIds = array_filter(explode(',', $userIds));
            }

            foreach ($userIds as $userId) {
                \DB::table('announcement_recipients')->insert([
                    'announcement_id' => $id,
                    'user_id' => (int) $userId,
                ]);
            }
        }

        // Kung class — kunin lahat ng students sa class
        if ($request->target_type === 'class' && $request->class_id) {
            $students = \DB::table('class_students')
                ->join('students', 'class_students.student_id', '=', 'students.id')
                ->where('class_students.class_id', $request->class_id)
                ->where('class_students.status', 'active')
                ->select('students.user_id')
                ->get();

            foreach ($students as $s) {
                if ($s->user_id) {
                    \DB::table('announcement_recipients')->insert([
                        'announcement_id' => $id,
                        'user_id' => $s->user_id,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Announcement sent successfully',
            'id' => $id,
        ]);
    }

    public function unreadCount(Request $request)
    {
        $now = now();
        $user = $request->user();

        $count = \DB::table('announcements')
            ->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('expire_date')
                    ->orWhere('expire_date', '>=', $now);
            })
            ->where('publish_date', '<=', $now)
            ->where(function ($q) use ($user) {
                $q->whereIn('target_type', ['all', 'parents', 'students'])
                    ->orWhere(function ($q2) use ($user) {
                        $q2->whereIn('target_type', ['specific_students', 'specific_parents', 'class'])
                            ->whereExists(function ($q3) use ($user) {
                                $q3->select(\DB::raw(1))
                                    ->from('announcement_recipients')
                                    ->whereColumn('announcement_recipients.announcement_id', 'announcements.id')
                                    ->where('announcement_recipients.user_id', $user->id);
                            });
                    });
            })
            ->whereNotExists(function ($q) use ($user) {
                $q->select(\DB::raw(1))
                    ->from('announcement_reads')
                    ->whereColumn('announcement_reads.announcement_id', 'announcements.id')
                    ->where('announcement_reads.user_id', $user->id);
            })
            ->count();

        return response()->json(['success' => true, 'unread_count' => $count]);
    }

    public function markAllRead(Request $request)
    {
        $now = now();
        $user = $request->user();

        $announcements = \DB::table('announcements')
            ->where('status', 'active')
            ->where('publish_date', '<=', $now)
            ->whereNotExists(function ($q) use ($user) {
                $q->select(\DB::raw(1))
                    ->from('announcement_reads')
                    ->whereColumn('announcement_reads.announcement_id', 'announcements.id')
                    ->where('announcement_reads.user_id', $user->id);
            })
            ->pluck('id');

        foreach ($announcements as $announcementId) {
            \DB::table('announcement_reads')->insertOrIgnore([
                'announcement_id' => $announcementId,
                'user_id' => $user->id,
                'read_at' => $now,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function dismiss(Request $request, $id)
    {
        $user = $request->user();

        \DB::table('announcement_reads')->updateOrInsert(
            [
                'announcement_id' => $id,
                'user_id' => $user->id,
            ],
            [
                'read_at' => now(),
                'is_dismissed' => 1,
            ]
        );

        return response()->json(['success' => true]);
    }
}