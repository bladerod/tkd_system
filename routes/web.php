<?php

// use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Models\parentview;
use App\Models\beltview;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\ClassApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\AnnouncementApiController;
use App\Http\Controllers\Api\ParentApiController;



// ===========================================
// ALL API ROUTES HERE (TEMPORARY FIX)
// ===========================================

// Test route
Route::get('/api/test', function() {
    return response()->json([
        'success' => true,
        'message' => 'API is working from web.php!',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Public API routes
Route::post('/api/login', [AuthController::class, 'login']);

// Protected API routes (with auth middleware)
Route::middleware('auth:sanctum')->prefix('api')->group(function () {
    
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
    
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});








// Authentication Routes
// Guest routes 
Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});


// Auth routes 
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // for user management crud
    Route::get('/settings/user', [UserController::class, 'index'])->name('users.index');
    Route::post('/settings/user', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/hash/{hash}', [UserController::class, 'getByHash'])->name('users.by-hash');
    // end user management

    // for announcements
    Route::get('/announcement', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcement', [App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcement/{id}', [App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::put('/announcement/{id}', [App\Http\Controllers\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcement/{id}', [App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    // for announcements

    // for parent management crud
    Route::get('/parent', function () {
        $parentList = parentview::all();
        return view('parent', compact('parentList'));
    })->name('parent');
    // end parent management

    Route::get('/check-username', function(Request $request) {
        $username = $request->query('username');
        $userId = $request->query('user_id');
        
        $query = App\Models\User::where('username', $username);
        
        // If editing, exclude current user
        if ($userId) {
            $query->where('user_id', '!=', $userId);
        }
        
        return response()->json([
            'available' => !$query->exists()
        ]);
    })->name('check.username');


    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [App\Http\Controllers\AttendanceController::class, 'index'])->name('index');
        Route::post('/manual-override', [App\Http\Controllers\AttendanceController::class, 'manualOverride'])->name('manual-override');
        Route::post('/add-manual', [App\Http\Controllers\AttendanceController::class, 'addManual'])->name('add-manual');
        Route::get('/export', [App\Http\Controllers\AttendanceController::class, 'exportCsv'])->name('export');
    });

    Route::get('/instructor', function () {
        return view('instructor');
    });

    route::get('/billing', function(){
        return view('billing');
    });

    route::get('/settings/billing-rules',function(){
        return view('billingrules');
    });

    route::get('/settings', function(){
        return view('settings');
    });

    route::get('/competition', function(){
        return view('competition');
    });

    route::get('/settings/club-profile', function(){
        return view('clubprofile');
    });

    route::get('/settings/branding', function(){
        return view('branding');
    });

    route::get('/settings/branding-rules',function(){
        return view('brandingrules');
    });

    route::get('/settings/discounts',function(){
        return view('discounts');
    });

    route::get('/settings/roles-and-permissions',function(){
        return view('rolespermission');
    });

    route::get('/settings/device',function(){
        return view('device');
    });

    route::get('/settings/integration',function(){
        return view('integration');
    });

    Route::get('/chat', function () {
        return view('chat');
    })->name('chat');

    Route::get('/report', function () {
        return view('report');
    })->name('report');

    Route::get('/student', function () {
        $beltLevels = beltview::all();
        return view('student', compact('beltLevels'));
    })->name('student');

    Route::get('/certificates', function () {
        return view('certificates');
    })->name('certificates');

    Route::get('/test-edward', function () {
    return "Test push successful!";
});


});