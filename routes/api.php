<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\ClassApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\AnnouncementApiController;
use App\Http\Controllers\Api\ParentApiController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CertificateController;


// Public routes (no token needed)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/face-login', [StudentApiController::class, 'faceLogin']);
Route::post('/auth/face-checkin', [StudentApiController::class, 'faceCheckIn']);

// Test route to verify API is working
Route::get('/test', function() {
    return response()->json([
        'success' => true,
        'message' => 'API is working from api.php!',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Protected routes (token REQUIRED)
Route::middleware('auth:sanctum')->group(function () {

    // Get the authenticated user's info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    });

    // Dashboard
    Route::get('/dashboard/statistics', [DashboardApiController::class, 'statistics']);
    Route::get('/dashboard/attendance-trends', [DashboardApiController::class, 'attendanceTrends']);

    Route::get('/student/profile', [StudentApiController::class, 'myProfile']);

    Route::post('/student/register-face', [StudentApiController::class, 'registerFace']);

    Route::post('/student/reset-face', [StudentApiController::class, 'resetFace']);
    // Instructor-specific na class stats
    Route::get('/instructor/attendance-stats', [ClassApiController::class, 'attendanceStats']);

    // Students
    // Route::prefix('students')->group(function () {
    //     Route::get('/', [StudentApiController::class, 'index']);
    //     Route::get('/{id}', [StudentApiController::class, 'show']);
    //     Route::get('/{id}/attendance', [StudentApiController::class, 'attendance']);
    //     Route::get('/{id}/progress', [StudentApiController::class, 'progress']);
    // });

    // Attendance
    Route::get('/attendance', [AttendanceApiController::class, 'index']);
    Route::post('/classes/{id}/start-session', [ClassApiController::class, 'startSession']);
    Route::post('/attendance', [AttendanceApiController::class, 'store']);

    Route::get('/parents/{id}', [ParentsController::class, 'show']);
Route::get('/parents/{id}', [ParentsController::class, 'show']);
Route::get('/parents/{id}/billing', [ParentsController::class, 'billing']);
Route::get('/parents/{id}/payments', [ParentsController::class, 'payments']);
Route::get('/parents/{id}/chat', [ParentsController::class, 'chat']);
Route::get('/parents/{id}/activity', [ParentsController::class, 'activity']);
Route::get('/parents/{id}/notifications', [ParentsController::class, 'notifications']);

    Route::get('/chat-threads/{id}/messages', [ParentsController::class, 'getThreadMessages']);

    // Route::get('/certificates/students', [CertificateController::class, 'getStudentsForDropdown']);
    // Route::post('/certificates/generate', [CertificateController::class, 'generate']);
    // Route::get('/certificates/{id}', [CertificateController::class, 'show']);
    // Route::post('/certificates/{id}/email', [CertificateController::class, 'email']);
    // Route::post('/certificates/bulk-generate', [CertificateController::class, 'bulkGenerate']);


Route::get('/chat-threads/{threadId}/messages', [ChatController::class, 'messages']);
    Route::get('/chat-threads/{id}/messages', [StudentController::class, 'getThreadMessages']);


    Route::get('/parents/{id}', [ParentsController::class, 'show']);
    Route::get('/parents/{id}/children', [ParentsController::class, 'getChildrenDetails']);
    Route::get('/parents/{id}/billing', [ParentsController::class, 'getFamilyBilling']);
    Route::get('/parents/{id}/payments', [ParentsController::class, 'getPayments']);
    Route::get('/parents/{id}/activity', [ParentsController::class, 'getActivityLog']);
    Route::get('/parents/{id}/notifications', [ParentsController::class, 'getNotifications']);
    Route::post('/parents/{id}/send-message', [ParentsController::class, 'sendMessage']);

    Route::get('/chat-threads/{id}/messages', [ParentsController::class, 'getThreadMessages']);

    // Classes
    Route::prefix('classes')->group(function () {
    Route::get('/', [ClassApiController::class, 'index']);
    Route::get('/{id}', [ClassApiController::class, 'show']);
    Route::get('/{id}/sessions', [ClassApiController::class, 'sessions']);
});

Route::get('/instructor/classes', [ClassApiController::class, 'myClasses']);

Route::get('/classes/{id}/students', [ClassApiController::class, 'classStudents']);

Route::get('/certificates/students', [CertificateController::class,'getStudents']);
Route::get('/certificate-templates', [CertificateController::class,'getTemplates']);
Route::post('/certificates/generate', [CertificateController::class,'generate']);
Route::get('/certificate-templates', [CertificateController::class, 'getTemplates']);

Route::get('/students/{id}', [StudentController::class, 'show']);
Route::get('/students/{id}/attendance', [StudentController::class, 'attendance']);
Route::get('/students/{id}/competition', [StudentController::class, 'competitions']);
});

