<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\ClassApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\AnnouncementApiController;
use App\Http\Controllers\Api\ParentApiController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Dashboard
    Route::get('/dashboard/statistics', [DashboardApiController::class, 'statistics']);
    Route::get('/dashboard/attendance-trends', [DashboardApiController::class, 'attendanceTrends']);
    
    // Attendance
    Route::get('/attendance', [AttendanceApiController::class, 'index']);
    Route::post('/attendance/manual', [AttendanceApiController::class, 'manualOverride']);
    Route::post('/attendance/add', [AttendanceApiController::class, 'addManual']);
    
    // Students
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentApiController::class, 'index']);
        Route::get('/{id}', [StudentApiController::class, 'show']);
        Route::get('/{id}/attendance', [StudentApiController::class, 'attendance']);
        Route::get('/{id}/progress', [StudentApiController::class, 'progress']);
    });
    
    // Classes
    Route::prefix('classes')->group(function () {
        Route::get('/', [ClassApiController::class, 'index']);
        Route::get('/{id}', [ClassApiController::class, 'show']);
        Route::get('/{id}/sessions', [ClassApiController::class, 'sessions']);
        Route::get('/{id}/today-sessions', [ClassApiController::class, 'todaySessions']);
    });
    
    // Announcements
    Route::prefix('announcements')->group(function () {
        Route::get('/', [AnnouncementApiController::class, 'index']);
        Route::get('/{id}', [AnnouncementApiController::class, 'show']);
    });
    
    // Parent routes
    Route::prefix('parent')->group(function () {
        Route::get('/profile', [ParentApiController::class, 'profile']);
        Route::get('/child/{childId}', [ParentApiController::class, 'childDetails']);
        Route::get('/child/{childId}/attendance', [ParentApiController::class, 'childAttendance']);
    });
    
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user()->load(['branch', 'instructor', 'parent']);
    });
});