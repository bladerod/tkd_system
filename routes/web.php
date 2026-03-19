<?php

use App\Http\Controllers\InstructorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

    // USER MANAGEMENT
    Route::get('/settings/user', [UserController::class, 'index'])->name('users.index');
    Route::post('/settings/user', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // ANNOUNCEMENTS
    Route::get('/announcement', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcement', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcement/{id}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::put('/announcement/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcement/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // PARENTS
    Route::get('/parent', function () {
        $parentList = \App\Models\parentview::all();
        return view('parent', compact('parentList'));
    })->name('parent');

    // ATTENDANCE
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::post('/manual-override', [AttendanceController::class, 'manualOverride'])->name('manual-override');
        Route::post('/add-manual', [AttendanceController::class, 'addManual'])->name('add-manual');
        Route::get('/export', [AttendanceController::class, 'exportCsv'])->name('export');
    });

    // STUDENTS - Main fix here!
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}/profile', [StudentController::class, 'profile']);
    Route::get('/students/{student}/attendance', [StudentController::class, 'attendance']);
    Route::get('/students/{student}/billing', [StudentController::class, 'billing']);
    Route::get('/students/{student}/competition', [StudentController::class, 'competition']);
    Route::get('/students/{student}/certificates', [StudentController::class, 'certificates']);
    Route::get('/students/{student}/progress', [StudentController::class, 'progress']);
    Route::get('/students/{student}/chat', [StudentController::class, 'chat']);

    // CHAT
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{id}/send', [ChatController::class, 'send'])->name('chat.send');

    // OTHER PAGES


    Route::get('/instructor', [InstructorController::class, 'index'])->name('instructor.index');
    Route::post('/instructor/store', [InstructorController::class, 'store'])->name('instructor.store');
    Route::post('/instructor/update/{id}', [InstructorController::class, 'update'])->name('instructor.update');
    Route::delete('/instructor/delete/{id}', [InstructorController::class, 'destroy'])->name('instructor.delete');

    route::get('/competition',function(){
        return view('competition');
    });

    Route::get('/billing', function () {
        return view('billing');
    });

    Route::get('/report', function () {
        return view('report');
    })->name('report');

    // SETTINGS
    Route::get('/settings', function () {
        return view('settings');
    });
    Route::get('/settings/user', [UserController::class, 'index'])->name('users.index');
    Route::get('/settings/billing-rules', function () {
        return view('billingrules');
    });
    
    // For Club Profile
    Route::get('/settings/club-profile', [App\Http\Controllers\ClubProfileController::class, 'index'])->name('settings.club-profile');
    Route::post('/settings/club-profile/update', [App\Http\Controllers\ClubProfileController::class, 'update'])->name('settings.club-profile.update');
    //
    
    // For settings Branding
    Route::get('/settings/branding', [App\Http\Controllers\BrandingController::class, 'index'])->name('settings.branding');
    Route::post('/settings/branding/update', [App\Http\Controllers\BrandingController::class, 'update'])->name('settings.branding.update');
    //

    Route::get('/settings/branding-rules', function () {
        return view('brandingrules');
    });
    Route::get('/settings/discounts', function () {
        return view('discounts');
    });
    Route::get('/settings/roles-and-permissions', function () {
        return view('rolespermission');
    });
    Route::get('/settings/device', function () {
        return view('device');
    });
    Route::get('/settings/integration', function () {
        return view('integration');
    });

    Route::get('/chat', function () {
        return view('chat');
    })->name('chat');

    Route::get('/report', function () {
        return view('report');
    })->name('report');


    Route::get('/student', [StudentController::class,'index'])->name('index');
    Route::post('/student', [StudentController::class,'store'])->name('student.store');


    Route::get('/chat',[ChatController::class,'index'])->name('chat.index');

    Route::get('/chat/{id}',[ChatController::class,'show'])->name('chat.show');

    Route::post('/chat/{id}/send',[ChatController::class,'send'])->name('chat.send');
    Route::get('/chat',[ChatController::class,'index'])->name('chat.index');

    Route::get('/chat/{id}',[ChatController::class,'show'])->name('chat.show');

    Route::post('/chat/{id}/send',[ChatController::class,'send'])->name('chat.send');

    Route::get('/report', function () {
        return view('report');
    })->name('report');

    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{id}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/{id}/print', [CertificateController::class, 'print'])->name('certificates.print');
    Route::get('/certificates/verify/{qrCode}', [CertificateController::class, 'verify'])->name('certificates.verify');

    
});
