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