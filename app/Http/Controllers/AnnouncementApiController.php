<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementApiController extends Controller
{
    /**
     * Get announcements for current user
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $announcements = Announcement::with('createdBy')
            ->active()
            ->forUser($user)
            ->orderBy('publish_date', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }

    /**
     * Get single announcement
     */
    public function show($id, Request $request)
    {
        $user = $request->user();

        $announcement = Announcement::with('createdBy')
            ->active()
            ->forUser($user)
            ->find($id);

        if (!$announcement) {
            return response()->json([
                'success' => false,
                'message' => 'Announcement not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $announcement
        ]);
    }
}