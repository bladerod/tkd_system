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

    public function myProfile(Request $request)
{
    $user = $request->user();
    
    // Find student linked to this user
    $student = \DB::table('students')
        ->where('user_id', $user->id)
        ->first();

    if (!$student) {
        return response()->json([
            'success' => false,
            'message' => 'Student profile not found'
        ], 404);
    }

    // Get belt name
    $belt = \DB::table('belt_levels')
        ->where('id', $student->current_belt)
        ->first();

    // Get class info
    $classStudent = \DB::table('class_students')
        ->where('student_id', $student->id)
        ->where('status', 'active')
        ->first();

    $class = null;
    $instructor = null;
    $schedule = null;

    if ($classStudent) {
        $class = \DB::table('classes')
            ->where('id', $classStudent->class_id)
            ->first();

        if ($class) {
            $instructorRecord = \DB::table('instructors')
                ->where('id', $class->primary_instructor_id)
                ->first();
            
            if ($instructorRecord) {
                $instructorUser = \DB::table('users')
                    ->where('id', $instructorRecord->user_id)
                    ->first();
                $instructor = $instructorUser ? trim($instructorUser->fname . ' ' . $instructorUser->lname) : 'TBA';
            }

            $schedule = \DB::table('class_schedules')
                ->where('class_id', $class->id)
                ->first();
        }
    }

    // Get attendance stats
    $totalClasses = \DB::table('attendance_logs')
        ->where('student_id', $student->id)
        ->count();

    $attended = \DB::table('attendance_logs')
        ->where('student_id', $student->id)
        ->whereIn('attendance_status', ['present', 'late'])
        ->count();

    return response()->json([
        'success' => true,
        'data' => [
            'name' => trim($student->first_name . ' ' . $student->last_name),
            'belt' => $belt->name ?? 'No Belt',
            'instructor' => $instructor ?? 'TBA',
            'next_class' => $schedule ? ucfirst($schedule->day_of_week) . ' ' . $schedule->start_time : 'No class scheduled',
            'classes_attended' => $attended,
            'total_classes' => $totalClasses,
            'branch' => $student->branch_id,
            'age' => $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->age : 0,
            'has_face' => !is_null($student->face_photo),
        ]
    ]);
}

/**
 * Register face photo ng student
 */
public function registerFace(Request $request)
{
    $user = $request->user();
    $student = \DB::table('students')->where('user_id', $user->id)->first();

    if (!$student) {
        return response()->json(['success' => false, 'message' => 'Student not found'], 404);
    }

    $request->validate([
        'face_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
    ]);

    $apiKey = '6854d593441f427794f106715b8e25e3';
    $name = trim($student->first_name . ' ' . $student->last_name);

    
    if ($student->luxand_person_id) {
        \Http::withHeaders(['token' => $apiKey])
            ->delete("https://api.luxand.cloud/person/{$student->luxand_person_id}");
    }

    //  create new person in luxand
  $createResponse = \Http::withHeaders(['token' => $apiKey])
    ->attach('photo', file_get_contents($request->file('face_photo')->path()), 'face.jpg')
    ->post('https://api.luxand.cloud/person', [
        'name' => $name,
    ]);

\Log::info('Luxand create person response:', $createResponse->json());

if (!$createResponse->successful()) {
    return response()->json(['success' => false, 'message' => 'Failed to create person in Luxand'], 500);
}

$personId = $createResponse->json('uuid');

$path = $request->file('face_photo')->store('face-photos', 'public');

// Search agad para makuha yung integer id
$searchResponse = \Http::withHeaders(['token' => $apiKey])
    ->attach('photo', file_get_contents($request->file('face_photo')->path()), 'face.jpg')
    ->post('https://api.luxand.cloud/photo/search');

\Log::info('Luxand search after register:', $searchResponse->json());

$intId = $searchResponse->json('0.id');

\DB::table('students')
    ->where('id', $student->id)
    ->update([
        'face_photo' => $path,
        'luxand_person_id' => (string)$intId,
    ]);

return response()->json([
    'success' => true,
    'message' => 'Face registered successfully',
]);
}

/**
 * Face login — compare uploaded photo sa lahat ng registered faces
 */
public function faceLogin(Request $request)
{
    $request->validate([
        'face_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
    ]);

    $apiKey = '6854d593441f427794f106715b8e25e3';

    $response = \Http::withHeaders(['token' => $apiKey])
        ->attach('photo', file_get_contents($request->file('face_photo')->path()), 'face.jpg')
        ->post('https://api.luxand.cloud/photo/search');

    \Log::info('Luxand search response:', $response->json());

    if (!$response->successful()) {
        return response()->json(['success' => false, 'message' => 'Face not recognized'], 401);
    }

    $results = $response->json();

    if (empty($results) || !isset($results[0]['name'])) {
        return response()->json(['success' => false, 'message' => 'Face not recognized'], 401);
    }

    $probability = $results[0]['probability'] ?? 0;

    if ($probability < 0.80) {
        return response()->json(['success' => false, 'message' => 'Face not recognized'], 401);
    }

   // Gamitin integer id mula sa Luxand
$luxandIntId = (string)($results[0]['id'] ?? null);

if (!$luxandIntId) {
    return response()->json(['success' => false, 'message' => 'Face not recognized'], 401);
}

// Hanapin sa database gamit integer id
$student = \DB::table('students')
    ->where('luxand_person_id', $luxandIntId)
    ->first();

if (!$student) {
    return response()->json(['success' => false, 'message' => 'Student not found'], 404);
}

    $user = \DB::table('users')->where('id', $student->user_id)->first();
    $belt = \DB::table('belt_levels')->where('id', $student->current_belt)->first();

    return response()->json([
        'success' => true,
        'student' => [
            'id' => $student->id,
            'user_id' => $user->id,
            'name' => trim($student->first_name . ' ' . $student->last_name),
            'belt' => $belt->name ?? 'No Belt',
            'age' => $student->birthdate
                ? \Carbon\Carbon::parse($student->birthdate)->age
                : 0,
            'photo' => $student->photo_url,
        ]
    ]);
}

/**
 * Confirm check-in — called pag pinindot ng student ang CHECK IN button
 */
public function faceCheckIn(Request $request)
{
    $request->validate([
        'user_id' => 'required|integer',
    ]);

    $user = \DB::table('users')->where('id', $request->user_id)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    // Create sanctum token
    $userModel = \App\Models\User::find($user->id);
    $token = $userModel->createToken('face-login')->plainTextToken;

    return response()->json([
        'success' => true,
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => trim($user->fname . ' ' . $user->lname),
            'email' => $user->email,
            'role' => $user->role,
        ]
    ]);
}

/**
 * Simple image comparison using GD
 */
private function compareImages(string $path1, string $path2): float
{
    try {
        $img1 = $this->loadImage($path1);
        $img2 = $this->loadImage($path2);

        if (!$img1 || !$img2) return 0;

        // Resize both to 16x16 for comparison
        $small1 = imagecreatetruecolor(16, 16);
        $small2 = imagecreatetruecolor(16, 16);
        imagecopyresampled($small1, $img1, 0, 0, 0, 0, 16, 16, imagesx($img1), imagesy($img1));
        imagecopyresampled($small2, $img2, 0, 0, 0, 0, 16, 16, imagesx($img2), imagesy($img2));

        $diff = 0;
        $total = 16 * 16 * 3; // RGB channels

        for ($x = 0; $x < 16; $x++) {
            for ($y = 0; $y < 16; $y++) {
                $c1 = imagecolorat($small1, $x, $y);
                $c2 = imagecolorat($small2, $x, $y);

                $r1 = ($c1 >> 16) & 0xFF;
                $g1 = ($c1 >> 8) & 0xFF;
                $b1 = $c1 & 0xFF;

                $r2 = ($c2 >> 16) & 0xFF;
                $g2 = ($c2 >> 8) & 0xFF;
                $b2 = $c2 & 0xFF;

                $diff += abs($r1 - $r2) + abs($g1 - $g2) + abs($b1 - $b2);
            }
        }

        imagedestroy($small1);
        imagedestroy($small2);
        imagedestroy($img1);
        imagedestroy($img2);

        $similarity = (1 - ($diff / ($total * 255))) * 100;
        return $similarity;

    } catch (\Exception $e) {
        return 0;
    }
}

private function loadImage(string $path)
{
    $type = exif_imagetype($path);
    return match($type) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($path),
        IMAGETYPE_PNG => imagecreatefrompng($path),
        default => null,
    };
}

public function resetFace(Request $request)
{
    $user = $request->user();
    $student = \DB::table('students')->where('user_id', $user->id)->first();

    if (!$student) {
        return response()->json(['success' => false, 'message' => 'Student not found'], 404);
    }

    \DB::table('students')->where('id', $student->id)->update(['face_photo' => null]);

    return response()->json(['success' => true, 'message' => 'Face data reset successfully']);
}

}