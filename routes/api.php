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

    // Students
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentApiController::class, 'index']);
        Route::get('/{id}', [StudentApiController::class, 'show']);
        Route::get('/{id}/attendance', [StudentApiController::class, 'attendance']);
        Route::get('/{id}/progress', [StudentApiController::class, 'progress']);
    });

    // Attendance
    Route::get('/attendance', [AttendanceApiController::class, 'index']);
    Route::post('/attendance', [AttendanceApiController::class, 'store']);

    Route::get('/parents/{id}', [ParentsController::class, 'show']);
    Route::get('/parents/{id}/children', [ParentsController::class, 'getChildrenDetails']);
    Route::get('/parents/{id}/billing', [ParentsController::class, 'getFamilyBilling']);
    Route::get('/parents/{id}/payments', [ParentsController::class, 'getPayments']);
    Route::get('/parents/{id}/activity', [ParentsController::class, 'getActivityLog']);
    Route::get('/parents/{id}/notifications', [ParentsController::class, 'getNotifications']);
    Route::post('/parents/{id}/send-message', [ParentsController::class, 'sendMessage']);

    Route::get('/chat-threads/{id}/messages', [ParentsController::class, 'getThreadMessages']);

    Route::get('/certificates/students', [CertificateController::class, 'getStudentsForDropdown']);
    Route::post('/certificates/generate', [CertificateController::class, 'generate']);
    Route::get('/certificates/{id}', [CertificateController::class, 'show']);
    Route::post('/certificates/{id}/email', [CertificateController::class, 'email']);
    Route::post('/certificates/bulk-generate', [CertificateController::class, 'bulkGenerate']);


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


Route::get('/certificates/students', [CertificateController::class, 'getStudents']);
Route::post('/certificates/generate', [CertificateController::class, 'generate']);
Route::get('/certificates/{id}', [CertificateController::class, 'show']);
Route::post('/certificates/{id}/email', [CertificateController::class, 'email']);
Route::post('/certificates/bulk-generate', [CertificateController::class, 'bulkGenerate']);
});
