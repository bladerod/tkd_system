<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BillingRulesController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BrandingController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClubProfileController;
use App\Http\Controllers\DashboardPopulateController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Models\BeltLevel;
use App\Models\Classes;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

// Auth routes
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardPopulateController::class, 'index'])->name('dashboard.index');
    Route::post('/dashboard', [DashboardPopulateController::class, 'store'])->name('dashboard.store');
    Route::post('/dashboard',[ParentsController::class,'store'])->name('dashboard.store');


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
    Route::post('/student', [StudentController::class,'store'])->name('student.store');

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

    // Class Management Routes
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/', [ClassController::class, 'index'])->name('index');
        Route::post('/', [ClassController::class, 'store'])->name('store');
        Route::get('/{id}', [ClassController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ClassController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ClassController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClassController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/available-students', [ClassController::class, 'getAvailableStudents'])->name('available-students');
        Route::post('/{id}/enroll', [ClassController::class, 'enrollStudent'])->name('enroll');
        Route::delete('/{classId}/students/{studentId}', [ClassController::class, 'removeStudent'])->name('remove-student');
        Route::get('/export/csv', [ClassController::class, 'exportCsv'])->name('export');

    });

    Route::get('/billing', function () {
        return view('billing');
    });


    // SETTINGS
    Route::get('/settings', function () {
        return view('settings');
    });
    Route::get('/settings/user', [UserController::class, 'index'])->name('users.index');

    //Billing Rules
    Route::get('/settings/billing-rules', [BillingRulesController::class, 'index']);
    Route::post('/settings/billing-rules', [BillingRulesController::class, 'update']);
    //

    // For Club Profile
    Route::get('/settings/club-profile', [ClubProfileController::class, 'index'])->name('settings.club-profile');
    Route::post('/settings/club-profile/update', [ClubProfileController::class, 'update'])->name('settings.club-profile.update');
    //

    // For settings Branding
    Route::get('/settings/branding', [BrandingController::class, 'index'])->name('settings.branding');
    Route::post('/settings/branding/update', [BrandingController::class, 'update'])->name('settings.branding.update');
    //

    Route::get('/settings/branding-rules', function () {
        return view('brandingrules');
    });

    // DISCOUNTS
    Route::get('/settings/discounts', [DiscountController::class, 'index'])->name('discounts.index');
    Route::post('/settings/discounts',[DiscountController::class,'store'])->name('discounts.store');
    Route::get('/settings/discounts', [DiscountController::class, 'index'])->name('discounts.index');
    Route::get('/discounts/{id}', [DiscountController::class, 'show'])->name('discounts.show');
    Route::put('/discounts/{id}', [DiscountController::class, 'update'])->name('discounts.update');
    Route::delete('/discounts/{id}', [DiscountController::class, 'destroy'])->name('discounts.destroy');
    //

    Route::get('/settings/roles-and-permissions', function () {
        return view('rolespermission');
    });
    Route::get('/settings/device', function () {
        return view('device');
    });
    Route::get('/settings/integration', function () {
        return view('integration');
    });


    Route::post('/branch/check', [BranchController::class, 'checkField'])->name('branch.check');
    Route::get('/branch', [BranchController::class,'index'])->name('branch');
    Route::post('/branch/store', [BranchController::class,'store'])->name('branch.store');
    Route::post('/branch/update/{id}', [BranchController::class,'update'])->name('branch.update');
    Route::delete('/branch/delete/{id}', [BranchController::class,'destroy'])->name('branch.delete');







    Route::get('/report', function () {
        return view('report');
    })->name('report');

Route::get('/student', function () {
    $beltlevels = BeltLevel::all();
    $users = User::all();
    $classes = Classes::all();
     $vwstudents = Student::select(
        'id',
        'student_name',
        'current_belt',
        'status',
        'parent_name',
        'balance',
        'attendance'
    )->get();

    return view('student', compact('vwstudents', 'classes', 'users', 'beltlevels'));
})->name('student');

    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{id}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/{id}/print', [CertificateController::class, 'print'])->name('certificates.print');
    Route::get('/certificates/verify/{qrCode}', [CertificateController::class, 'verify'])->name('certificates.verify');

    // USERNAME CHECK (for user management)
    Route::get('/check-username', function (Request $request) {
        $username = $request->query('username');
        $userId = $request->query('user_id');
        $query = User::where('username', $username);
        if ($userId) {
            $query->where('user_id', '!=', $userId);
        }
        return response()->json(['available' => !$query->exists()]);
    })->name('check.username');
});
