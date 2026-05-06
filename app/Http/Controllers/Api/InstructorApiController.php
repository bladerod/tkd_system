<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InstructorApiController extends Controller
{
    public function myProfile(Request $request)
{
    try {
        $user = Auth::user();

        $instructor = DB::table('instructors as i')
            ->leftJoin('users as u', 'i.user_id', '=', 'u.id')
            ->leftJoin('branches as b', 'u.branch_id', '=', 'b.id')
            ->where('i.user_id', $user->id)
            ->select(
                'i.id as instructor_id',
                'i.fname',
                'i.lname',
                'i.email',
                'i.contact as mobile',
                'i.photo',
                'b.name as branch_name'
            )
            ->first();

        if (!$instructor) {
            return response()->json(['success' => false, 'message' => 'Instructor not found'], 404);
        }

        // Get assigned classes
        $classes = DB::table('classes as c')
            ->leftJoin('class_schedules as cs', 'c.id', '=', 'cs.class_id')
            ->where('c.primary_instructor_id', $instructor->instructor_id)
            ->where('c.status', 'active')
            ->select(
                'c.id',
                'c.class_name',
                'c.level',
                'c.status',
                DB::raw('GROUP_CONCAT(CONCAT(UPPER(LEFT(cs.day_of_week,3)), " ", TIME_FORMAT(cs.start_time, "%h:%i %p")) SEPARATOR " / ") as schedule')
            )
            ->groupBy('c.id', 'c.class_name', 'c.level', 'c.status')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'instructor_id' => $instructor->instructor_id,
                'full_name'     => trim($instructor->fname . ' ' . $instructor->lname),
                'email'         => $instructor->email,
                'mobile'        => $instructor->mobile,
                'photo_url'     => $instructor->photo,
                'branch'        => $instructor->branch_name ?? 'N/A',
                'classes'       => $classes,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'mobile' => 'nullable|string|max:20',
                'email'  => 'nullable|email|max:100',
            ]);

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'mobile' => $request->mobile,
                    'email'  => $request->email,
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updatePhoto(Request $request)
{
    try {
        $user = Auth::user();

        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $path = $request->file('photo')->store('instructor_photos', 'public');
        $url = Storage::url($path);

        DB::table('users')
            ->where('id', $user->id)
            ->update(['photo_url' => $url]);

        DB::table('instructors')
            ->where('user_id', $user->id)
            ->update(['photo' => $url]);

        return response()->json([
            'success'   => true,
            'message'   => 'Photo updated successfully!',
            'photo_url' => $url,
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
}