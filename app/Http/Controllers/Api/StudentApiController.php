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
    
    $student = \DB::table('students')
        ->where('user_id', $user->id)
        ->first();

    if (!$student) {
        return response()->json([
            'success' => false,
            'message' => 'Student profile not found'
        ], 404);
    }

    $request->validate([
        'face_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
    ]);

    // Save photo
    $path = $request->file('face_photo')->store('face-photos', 'public');

    // Update student face_photo
    \DB::table('students')
        ->where('id', $student->id)
        ->update(['face_photo' => $path]);

    return response()->json([
        'success' => true,
        'message' => 'Face registered successfully',
        'face_photo' => $path,
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

    // Get all students with registered faces
    $students = \DB::table('students')
        ->whereNotNull('face_photo')
        ->get();

    if ($students->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No registered faces found'
        ], 404);
    }

    // Save uploaded photo temporarily
    $uploadedPath = $request->file('face_photo')->store('temp-faces', 'public');
    $uploadedFullPath = storage_path('app/public/' . $uploadedPath);

    $matchedStudent = null;
    $bestScore = 0;

    foreach ($students as $student) {
        $storedFullPath = storage_path('app/public/' . $student->face_photo);
        
        if (!file_exists($storedFullPath)) continue;

        // Simple image comparison using GD
        $score = $this->compareImages($uploadedFullPath, $storedFullPath);
        
        if ($score > $bestScore) {
            $bestScore = $score;
            $matchedStudent = $student;
        }
    }

    // Delete temp file
    \Storage::disk('public')->delete($uploadedPath);

    // Threshold — dapat 70% match minimum
    if ($bestScore < 70 || !$matchedStudent) {
        return response()->json([
            'success' => false,
            'message' => 'Face not recognized'
        ], 401);
    }

    // Get user linked to student
    $user = \DB::table('users')->where('id', $matchedStudent->user_id)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User account not found'
        ], 404);
    }

    // Get belt
    $belt = \DB::table('belt_levels')->where('id', $matchedStudent->current_belt)->first();

    // Return student info for confirmation card — hindi pa mag-login agad
    return response()->json([
        'success' => true,
        'student' => [
            'id' => $matchedStudent->id,
            'user_id' => $user->id,
            'name' => trim($matchedStudent->first_name . ' ' . $matchedStudent->last_name),
            'belt' => $belt->name ?? 'No Belt',
            'age' => $matchedStudent->birthdate 
                ? \Carbon\Carbon::parse($matchedStudent->birthdate)->age 
                : 0,
            'photo' => $matchedStudent->photo_url,
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