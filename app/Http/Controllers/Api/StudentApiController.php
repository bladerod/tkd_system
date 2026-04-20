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
            $query->where(function ($q) use ($search) {
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
            'classes' => function ($q) {
                $q->wherePivot('status', 'active');
            },
            'attendanceLogs' => function ($q) {
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
            ->when($request->from_date, function ($q, $from) {
                $q->whereDate('checkin_time', '>=', $from);
            })
            ->when($request->to_date, function ($q, $to) {
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
            'beltExamResults' => function ($q) {
                $q->with('exam')->latest();
            },
            'skillProgress.skill',
            'evaluations' => function ($q) {
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

        $apiKey = '91a45ab21a264242b6afe483819721b0';
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
                'luxand_person_id' => (string) $intId,
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

        $apiKey = '91a45ab21a264242b6afe483819721b0';

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
        $luxandIntId = (string) ($results[0]['id'] ?? null);

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

    public function myAttendance(Request $request)
{
    $user = $request->user();
    $student = \DB::table('students')->where('user_id', $user->id)->first();

    if (!$student) {
        return response()->json(['success' => false, 'message' => 'Student not found'], 404);
    }

    $records = \DB::table('attendance_logs')
        ->where('student_id', $student->id)
        ->orderBy('checkin_time', 'desc')
        ->get()
        ->map(function ($log) {
            $checkinTime = $log->checkin_time;
            $method = match($log->method) {
                'face_scan' => 'Face Scan',
                'manual'    => 'Manual',
                'face'      => 'Face Scan',
                'qr'        => 'QR Code',
                default     => 'Manual',
            };

            return [
                'day'    => \Carbon\Carbon::parse($checkinTime)->format('j'),
                'month'  => strtoupper(\Carbon\Carbon::parse($checkinTime)->format('M')),
                'status' => ucfirst($log->attendance_status),
                'method' => $method,
                'time'   => $log->attendance_status === 'absent'
                    ? '-'
                    : \Carbon\Carbon::parse($checkinTime)->format('g:i A'),
            ];
        });

    $present = $records->where('status', 'Present')->count();
    $absent  = $records->where('status', 'Absent')->count();
    $late    = $records->where('status', 'Late')->count();

    return response()->json([
        'success' => true,
        'data'    => [
            'summary' => [
                'present' => $present,
                'absent'  => $absent,
                'late'    => $late,
            ],
            'records' => $records->values(),
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
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    $student = \DB::table('students')->where('user_id', $user->id)->first();

    if (!$student) {
        return response()->json(['success' => false, 'message' => 'Student not found'], 404);
    }

    $now = now();

    \DB::table('active_logins')->updateOrInsert(
        ['student_id' => $student->id],
        [
            'login_type'   => 'face_scan',
            'logged_in_at' => $now,
            'expires_at'   => $now->copy()->endOfDay(),
        ]
    );

    $userModel = \App\Models\User::find($user->id);
    $token = $userModel->createToken('face-login')->plainTextToken;

    return response()->json([
        'success'    => true,
        'token'      => $token,
        'login_type' => 'face_scan',
        'user'       => [
            'id'    => $user->id,
            'name'  => trim($user->fname . ' ' . $user->lname),
            'email' => $user->email,
            'role'  => $user->role,
        ]
    ]);
}

public function getClassStudentsWithLoginType(Request $request, $classId)
{
    $class = \DB::table('classes')->where('id', $classId)->first();

    if (!$class) {
        return response()->json(['success' => false, 'message' => 'Class not found'], 404);
    }

    $students = \DB::table('class_students')
        ->where('class_students.class_id', $classId)
        ->where('class_students.status', 'active')
        ->join('students', 'class_students.student_id', '=', 'students.id')
        ->join('belt_levels', 'students.current_belt', '=', 'belt_levels.id')
        ->leftJoin('active_logins', function($join) {
            $join->on('students.id', '=', 'active_logins.student_id')
                 ->where('active_logins.expires_at', '>', now());
        })
        ->select(
            'students.id',
            'students.first_name',
            'students.last_name',
            'belt_levels.name as belt',
            \DB::raw("COALESCE(active_logins.login_type, 'not_logged_in') as login_type")
        )
        ->get()
        ->map(function ($s) {
            return [
                'id'         => $s->id,
                'name'       => trim($s->first_name . ' ' . $s->last_name),
                'belt'       => $s->belt,
                'login_type' => $s->login_type,
            ];
        });

    return response()->json([
        'success' => true,
        'data'    => [
            'class'    => $class,
            'students' => $students,
        ]
    ]);
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

    public function studentsList(Request $request)
{
    $user = $request->user();

    // Kunin yung instructor record
    $instructor = \DB::table('instructors')->where('user_id', $user->id)->first();

    if (!$instructor) {
        return response()->json(['success' => false, 'message' => 'Instructor not found'], 404);
    }

    // Kunin lahat ng students sa classes ng instructor
    $students = \DB::table('students')
        ->join('class_students', 'students.id', '=', 'class_students.student_id')
        ->join('classes', 'class_students.class_id', '=', 'classes.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->where('classes.primary_instructor_id', $instructor->id)
        ->where('class_students.status', 'active')
        ->select(
            'students.id',
            'students.first_name',
            'students.last_name',
            'users.id as user_id',
            'classes.class_name as class_name'
        )
        ->distinct()
        ->get()
        ->map(function ($s) {
            return [
                'id'       => $s->user_id,
                'name'     => trim($s->first_name . ' ' . $s->last_name),
                'subtitle' => $s->class_name,
            ];
        });

    return response()->json(['success' => true, 'data' => $students]);
}

public function parentsList(Request $request)
{
    $user = $request->user();

    $instructor = \DB::table('instructors')->where('user_id', $user->id)->first();

    if (!$instructor) {
        return response()->json(['success' => false, 'message' => 'Instructor not found'], 404);
    }

    // Kunin lahat ng parents ng students sa classes ng instructor
    $parents = \DB::table('parents')
        ->join('parent_students', 'parents.id', '=', 'parent_students.parent_id')
        ->join('students', 'parent_students.student_id', '=', 'students.id')
        ->join('class_students', 'students.id', '=', 'class_students.student_id')
        ->join('classes', 'class_students.class_id', '=', 'classes.id')
        ->join('users', 'parents.user_id', '=', 'users.id')
        ->where('classes.primary_instructor_id', $instructor->id)
        ->where('class_students.status', 'active')
        ->select(
            'parents.id',
            'users.id as user_id',
            'users.fname',
            'users.lname',
            'students.first_name as student_fname',
            'students.last_name as student_lname'
        )
        ->distinct()
        ->get()
        ->map(function ($p) {
            return [
                'id'       => $p->user_id,
                'name'     => trim($p->fname . ' ' . $p->lname),
                'subtitle' => 'Parent of ' . trim($p->student_fname . ' ' . $p->student_lname),
            ];
        });

    return response()->json(['success' => true, 'data' => $parents]);
}

}