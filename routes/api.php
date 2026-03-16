<?php
use App\Http\Controllers\Api\AttendanceApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Public route for the Flutter app to log in and get a token
Route::post('/login', [AuthController::class, 'login']);

// Protected routes that require the token
Route::middleware('auth:sanctum')->group(function () {

    // Get the authenticated user's info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Attendance API routes
    Route::get('/attendance', [AttendanceApiController::class, 'index']);
    Route::post('/attendance', [AttendanceApiController::class, 'store']);
});


use App\Http\Controllers\StudentController;

Route::middleware('auth')->group(function () {
    Route::get('/students/{id}', [StudentController::class, 'show']);
    Route::get('/students/{id}/profile', [StudentController::class, 'getProfile']);
    Route::get('/students/{id}/attendance', [StudentController::class, 'getAttendance']);
    Route::get('/students/{id}/billing', [StudentController::class, 'getBilling']);
    Route::get('/students/{id}/competitions', [StudentController::class, 'getCompetitions']);
    Route::get('/students/{id}/certificates', [StudentController::class, 'getCertificates']);
    Route::get('/students/{id}/progress', [StudentController::class, 'getProgress']);
    Route::get('/students/{id}/documents', [StudentController::class, 'getDocuments']);
    Route::post('/students/{id}/send-message', [StudentController::class, 'sendMessage']);

    Route::get('/chat-threads/{id}/messages', [StudentController::class, 'getThreadMessages']);
});

Route::middleware('auth')->group(function () {
    Route::get('/parents/{id}', [ParentController::class, 'show']);
    Route::get('/parents/{id}/children', [ParentController::class, 'getChildrenDetails']);
    Route::get('/parents/{id}/billing', [ParentController::class, 'getFamilyBilling']);
    Route::get('/parents/{id}/payments', [ParentController::class, 'getPayments']);
    Route::get('/parents/{id}/activity', [ParentController::class, 'getActivityLog']);
    Route::get('/parents/{id}/notifications', [ParentController::class, 'getNotifications']);
    Route::post('/parents/{id}/send-message', [ParentController::class, 'sendMessage']);

    Route::get('/chat-threads/{id}/messages', [ParentController::class, 'getThreadMessages']);
});

Route::middleware('auth')->group(function () {
    Route::get('/certificates/students', [CertificateController::class, 'getStudentsForDropdown']);
    Route::post('/certificates/generate', [CertificateController::class, 'generate']);
    Route::get('/certificates/{id}', [CertificateController::class, 'show']);
    Route::post('/certificates/{id}/email', [CertificateController::class, 'email']);
    Route::post('/certificates/bulk-generate', [CertificateController::class, 'bulkGenerate']);
});
