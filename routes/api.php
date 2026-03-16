<?php
use App\Http\Controllers\Api\AttendanceApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\StudentController;

// Public route for the Flutter app to log in and get a token

// Public routes (no token needed)
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


    Route::get('/parents/{id}', [ParentsController::class, 'show']);
    Route::get('/parents/{id}/children', [ParentsController::class, 'getChildrenDetails']);
    Route::get('/parents/{id}/billing', [ParentsController::class, 'getFamilyBilling']);
    Route::get('/parents/{id}/payments', [ParentsController::class, 'getPayments']);
    Route::get('/parents/{id}/activity', [ParentsController::class, 'getActivityLog']);
    Route::get('/parents/{id}/notifications', [ParentsController::class, 'getNotifications']);
    Route::post('/parents/{id}/send-message', [ParentsController::class, 'sendMessage']);

    Route::get('/chat-threads/{id}/messages', [ParentsController::class, 'getThreadMessages']);
});


