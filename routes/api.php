<!-- <?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\ClassApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\AnnouncementApiController;
use App\Http\Controllers\Api\ParentApiController;

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
    
    // Test token route
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
    Route::post('/attendance/manual', [AttendanceApiController::class, 'manualOverride']);
    Route::post('/attendance/add', [AttendanceApiController::class, 'addManual']);
    
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
}); -->