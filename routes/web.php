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
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\DashboardPopulateController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Models\BeltLevel;
use App\Models\Classes;
use App\Models\User;
// use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    // Password Reset Routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');

    Route::get('/reset-password', function(){
        return view('announcementAttendance');
    });
});

// Auth routes
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardPopulateController::class, 'index'])->middleware('permission:dashboard,view')->name('dashboard.index');
    Route::post('/dashboard/student', [DashboardPopulateController::class, 'store'])->middleware('permission:students,create')->name('dashboard.student.store');
    Route::post('/dashboard/parent', [ParentsController::class, 'store'])->middleware('permission:parents,create')->name('parent.store');
    Route::post('/dashboard/announcement', [DashboardPopulateController::class, 'announcement'])->middleware('permission:parents,create')->name('dashboard.announcement.store');



    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ANNOUNCEMENTS
    Route::get('/announcement', [AnnouncementController::class, 'index'])->middleware('permission:announcements,view')->name('announcements.index');
    Route::post('/announcement', [AnnouncementController::class, 'store'])->middleware('permission:announcements,create')->name('announcements.store');
    Route::get('/announcement/{id}', [AnnouncementController::class, 'show'])->middleware('permission:announcements,view')->name('announcements.show');
    Route::put('/announcement/{id}', [AnnouncementController::class, 'update'])->middleware('permission:announcements,edit')->name('announcements.update');
    Route::delete('/announcement/{id}', [AnnouncementController::class, 'destroy'])->middleware('permission:announcements,delete')->name('announcements.destroy');

    // PARENTS
    Route::get('/parents', [ParentsController::class, 'index'])->name('parents.index');
Route::get('/parents/{id}', [ParentsController::class, 'show']);
Route::get('/parents/{id}/billing', [ParentsController::class, 'billing']);
Route::get('/parents/{id}/payments', [ParentsController::class, 'payments']);
Route::get('/parents/{id}/chat', [ParentsController::class, 'chat']);
Route::get('/parents/{id}/activity', [ParentsController::class, 'activity']);
Route::get('/parents/{id}/notifications', [ParentsController::class, 'notifications']);
    Route::get('/parent', function () {
        $parentList = \App\Models\parentview::all();
        return view('parent', compact('parentList'));
    })->middleware('permission:parents,view')->name('parent');

    // ATTENDANCE
    Route::get('/attendance', [AttendanceController::class, 'index'])->middleware('permission:attendance,view')->name('attendance.index');
    Route::post('/attendance/add-manual', [AttendanceController::class, 'addManual'])->middleware('permission:attendance,create')->name('attendance.add-manual');
    Route::post('/attendance/manual-override', [AttendanceController::class, 'manualOverride'])->middleware('permission:attendance,edit')->name('attendance.manual-override');
    Route::get('/attendance/export', [AttendanceController::class, 'exportCsv'])->middleware('permission:attendance,view')->name('attendance.export');


    // STUDENTS
    Route::post('/student', [StudentController::class, 'store'])->name('student.create');
    Route::get('/students/{id}', [StudentController::class, 'show']);
    Route::get('/students/filter', [StudentController::class, 'filter']);
    Route::get('/students/{id}/attendance', [StudentController::class, 'attendance']);
    Route::get('/students/{id}/billing', [StudentController::class, 'billing']);
    Route::get('/students/{id}/competition', [StudentController::class, 'competitions']);
    Route::get('/students/{id}/certificates', [StudentController::class, 'certificates']);
    Route::get('/students/{student}/profile', [StudentController::class, 'profile']);
    Route::get('/students/{student}/attendance', [StudentController::class, 'attendance']);

    // Student Tabs
    Route::prefix('students/{student}')->middleware('permission:students,view')->group(function () {
        Route::get('/profile', [StudentController::class, 'profile']);
        Route::get('/attendance', [StudentController::class, 'attendance']);
        Route::get('/billing', [StudentController::class, 'billing']);
        Route::get('/competition', [StudentController::class, 'competition']);
        Route::get('/certificates', [StudentController::class, 'certificates']);
        Route::get('/progress', [StudentController::class, 'progress']);
        Route::get('/chat', [StudentController::class, 'chat']);
    });

    // CHAT
    Route::middleware(['auth'])->group(function () {

        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

        Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat.show');

        Route::post('/chat/{id}/send', [ChatController::class, 'send'])->name('chat.send');
        Route::post('/chat/create', [ChatController::class, 'create'])->name('chat.create');
    });

    // OTHER PAGES


    Route::get('/instructor', [InstructorController::class, 'index'])->name('instructor.index');
    Route::post('/instructor/store', [InstructorController::class, 'store'])->name('instructor.store');
    Route::post('/instructor/update/{id}', [InstructorController::class, 'update'])->name('instructor.update');
    Route::delete('/instructor/delete/{id}', [InstructorController::class, 'destroy'])->name('instructor.delete');

    // COMPETITION MANAGEMENT
    Route::prefix('competition')->name('competition.')->group(function () {
        Route::get('/', [CompetitionController::class, 'index'])->middleware('permission:competitions,view')->name('index');
        Route::post('/store', [CompetitionController::class, 'store'])->middleware('permission:competitions,create')->name('store');
        Route::get('/{id}', [CompetitionController::class, 'show'])->middleware('permission:competitions,view')->name('show');
        Route::get('/{id}/json', [CompetitionController::class, 'getCompetitionJson'])->middleware('permission:competitions,view')->name('json');
        Route::put('/{id}', [CompetitionController::class, 'update'])->middleware('permission:competitions,edit')->name('update');
        Route::delete('/{id}', [CompetitionController::class, 'destroy'])->middleware('permission:competitions,delete')->name('delete');

        // Competition Entry Routes
        Route::get('/{competitionId}/entries/create', [CompetitionController::class, 'addEntryForm'])->middleware('permission:competitions,create')->name('entries.create');
        Route::post('/{competitionId}/entries', [CompetitionController::class, 'storeEntry'])->middleware('permission:competitions,create')->name('entries.store');
        Route::get('/{competitionId}/entries/{entryId}/edit', [CompetitionController::class, 'editEntry'])->middleware('permission:competitions,edit')->name('entries.edit');
        Route::put('/{competitionId}/entries/{entryId}', [CompetitionController::class, 'updateEntry'])->middleware('permission:competitions,edit')->name('entries.update');
        Route::delete('/{competitionId}/entries/{entryId}', [CompetitionController::class, 'destroyEntry'])->middleware('permission:competitions,delete')->name('entries.destroy');
        Route::get('/{competitionId}/entries/{entryId}/json', [CompetitionController::class, 'getEntryJson'])->middleware('permission:competitions,view')->name('entries.json');
    });

    // CLASS MANAGEMENT
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/', [ClassController::class, 'index'])->middleware('permission:classes,view')->name('index');
        Route::post('/', [ClassController::class, 'store'])->middleware('permission:classes,create')->name('store');
        Route::get('/{id}', [ClassController::class, 'show'])->middleware('permission:classes,view')->name('show');
        Route::get('/{id}/edit', [ClassController::class, 'edit'])->middleware('permission:classes,edit')->name('edit');
        Route::put('/{id}', [ClassController::class, 'update'])->middleware('permission:classes,edit')->name('update');
        Route::delete('/{id}', [ClassController::class, 'destroy'])->middleware('permission:classes,delete')->name('destroy');
        Route::get('/{id}/available-students', [ClassController::class, 'getAvailableStudents'])->middleware('permission:classes,view')->name('available-students');
        Route::post('/{id}/enroll', [ClassController::class, 'enrollStudent'])->middleware('permission:classes,create')->name('enroll');
        Route::delete('/{classId}/students/{studentId}', [ClassController::class, 'removeStudent'])->middleware('permission:classes,delete')->name('remove-student');
        Route::get('/export/csv', [ClassController::class, 'exportCsv'])->middleware('permission:classes,view')->name('export');
    });

    // BILLING
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/invoices-json', [InvoiceController::class, 'getInvoicesJson'])
            ->middleware('permission:billing,view')
            ->name('invoices.json');
        Route::get('/', [InvoiceController::class, 'index'])->middleware('permission:billing,view')->name('index');
        Route::post('/generate-monthly', [InvoiceController::class, 'generateMonthlyInvoices'])->middleware('permission:billing,create')->name('generate-monthly');
        Route::post('/mark-overdue', [InvoiceController::class, 'markOverdueInvoices'])->middleware('permission:billing,edit')->name('mark-overdue');
        Route::post('/{invoiceId}/payment', [InvoiceController::class, 'processPayment'])->middleware('permission:billing,edit')->name('payment');
        Route::post('/{invoiceId}/reminder', [InvoiceController::class, 'sendReminder'])->middleware('permission:billing,view')->name('reminder');
        Route::get('/{invoiceId}/receipt', [InvoiceController::class, 'generateReceipt'])->middleware('permission:billing,view')->name('receipt');
    });
    Route::post('/billing/create-invoice', [InvoiceController::class, 'createInvoice'])->name('billing.create.invoice');
    Route::post('/billing/get-discount', [InvoiceController::class, 'getDiscountAmount'])->name('billing.get.discount');
    Route::post('/billing/get-penalty', [InvoiceController::class, 'getPenaltyAmount'])->name('billing.get.penalty');
    Route::post('/billing/create-invoice', [InvoiceController::class, 'createInvoice'])->name('billing.create.invoice');

    // Plans management
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans/store', [PlanController::class, 'store'])->name('plans.store');
    Route::get('/plans/{plan}/edit', [PlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');


    // BRANCHES
    Route::post('/branch/check', [BranchController::class, 'checkField'])->middleware('permission:branches,view')->name('branch.check');
    Route::get('/branch', [BranchController::class, 'index'])->middleware('permission:branches,view')->name('branch');
    Route::post('/branch/store', [BranchController::class, 'store'])->middleware('permission:branches,create')->name('branch.store');
    Route::post('/branch/update/{id}', [BranchController::class, 'update'])->middleware('permission:branches,edit')->name('branch.update');
    Route::delete('/branch/delete/{id}', [BranchController::class, 'destroy'])->middleware('permission:branches,delete')->name('branch.delete');

    // REPORTS
    Route::prefix('reports')->middleware('permission:reports,view')->group(function () {
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('/billing', [ReportController::class, 'billing'])->name('reports.billing');
        Route::get('/instructor', [ReportController::class, 'instructor'])->name('reports.instructor');
    });

   Route::get('/student', function (\Illuminate\Http\Request $request) {
    $beltlevels = BeltLevel::all();
    $users = User::all();
    $classes = Classes::all();

    $query = DB::table('student_overview')
        ->orderBy('student_name', 'asc')
        ->select(
            'id',
            'branch_name',
            'student_code',
            'student_name',
            'current_belt',
            'status',
            'parent_name',
            'balance',
            'attendance',
            'join_date'
        );

    // Status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Belt filter
    if ($request->filled('belt')) {
        $query->where('current_belt', $request->belt);
    }

    // Quick search
    if ($request->filled('search')) {
        $query->where('student_name', 'like', '%' . $request->search . '%')
      ->orWhere('parent_name', 'like', '%' . $request->search . '%');
    }
// Date From filter
if ($request->filled('date_from')) {
    $query->whereDate('join_date', '>=', $request->date_from);
}

// Date To filter
if ($request->filled('date_to')) {
    $query->whereDate('join_date', '<=', $request->date_to);
}
    $vwstudents = $query->get();

    return view('student', compact('vwstudents', 'classes', 'users', 'beltlevels'));
})->name('student');

    Route::get('/certificates', [CertificateController::class, 'index']);

    /* CRUD */
    Route::post('/api/certificates', [CertificateController::class, 'store']);
    Route::delete('/api/certificates/{id}', [CertificateController::class, 'destroy']);

    /* VIEW */
    Route::get('/certificates/{id}', [CertificateController::class, 'show']);
    Route::get('/certificates/{id}/download', [CertificateController::class, 'download']);

    /* PREVIEW */
    Route::post('/certificates/preview', [CertificateController::class, 'preview']);

    // /* VERIFY */
    // Route::get('/verify/{code}', [CertificateController::class, 'verify']);
    // Route::get('/templates', [CertificateController::class, 'templates']);
    // Route::get('/templates/create', [CertificateController::class, 'createTemplate']);
    // Route::post('/templates/store', [CertificateController::class, 'storeTemplate']);
    // Route::get('/templates/{id}/editor', [CertificateController::class, 'editor']);
    // Route::post('/templates/{id}/save-layout', [CertificateController::class, 'saveLayout']);
    // Route::post('/templates/{id}/upload-image', [CertificateController::class, 'uploadImage']);
    // Route::post('/templates/{id}/upload-bg', [CertificateController::class, 'uploadBackground']);

    Route::prefix('settings')->group(function () {

        // User Management
        Route::prefix('user')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->middleware('permission:users,view')->name('index');
            Route::post('/', [UserController::class, 'store'])->middleware('permission:users,create')->name('store');
            Route::get('/{id}', [UserController::class, 'show'])->middleware('permission:users,view')->name('show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->middleware('permission:users,edit')->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->middleware('permission:users,edit')->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('permission:users,delete')->name('destroy');
        });

        // Skill Checklist
        Route::get('/skill-checklist', [\App\Http\Controllers\SkillChecklistController::class, 'index'])->middleware('permission:settings,view')->name('settings.skill-checklist');
        Route::post('/skill-checklist', [\App\Http\Controllers\SkillChecklistController::class, 'store'])->middleware('permission:settings,create')->name('settings.skill-checklist.store');
        Route::put('/skill-checklist/{id}', [\App\Http\Controllers\SkillChecklistController::class, 'update'])->middleware('permission:settings,edit')->name('settings.skill-checklist.update');
        Route::delete('/skill-checklist/{id}', [\App\Http\Controllers\SkillChecklistController::class, 'destroy'])->middleware('permission:settings,delete')->name('settings.skill-checklist.destroy');

        // Billing Rules
        Route::get('/billing-rules', [BillingRulesController::class, 'index'])->middleware('permission:settings,view');
        Route::post('/billing-rules', [BillingRulesController::class, 'update'])->middleware('permission:settings,edit');

        // Club Profile
        Route::get('/club-profile', [ClubProfileController::class, 'index'])->middleware('permission:settings,view')->name('settings.club-profile');
        Route::post('/club-profile/update', [ClubProfileController::class, 'update'])->middleware('permission:settings,edit')->name('settings.club-profile.update');

        // Branding
        Route::get('/branding', [BrandingController::class, 'index'])->middleware('permission:settings,view')->name('settings.branding');
        Route::post('/branding/update', [BrandingController::class, 'update'])->middleware('permission:settings,edit')->name('settings.branding.update');
        Route::get('/branding-rules', function () {
            return view('brandingrules');
        })->middleware('permission:settings,view');

        Route::post('billing/{invoiceId}/approve-proof', [InvoiceController::class, 'approveProof'])->name('billing.approve.proof');
        Route::post('billing/{invoiceId}/reject-proof', [InvoiceController::class, 'rejectProof'])->name('billing.reject.proof');

        // Discounts
        Route::get('/discounts', [DiscountController::class, 'index'])->middleware('permission:settings,view')->name('discounts.index');
        Route::post('/discounts', [DiscountController::class, 'store'])->middleware('permission:settings,create')->name('discounts.store');
        Route::get('/discounts/{id}', [DiscountController::class, 'show'])->middleware('permission:settings,view')->name('discounts.show');
        Route::put('/discounts/{id}', [DiscountController::class, 'update'])->middleware('permission:settings,edit')->name('discounts.update');
        Route::delete('/discounts/{id}', [DiscountController::class, 'destroy'])->middleware('permission:settings,delete')->name('discounts.destroy');

        // Roles and Permissions
        Route::prefix('roles-and-permissions')->name('settings.roles-permissions.')->group(function () {
            Route::get('/', [\App\Http\Controllers\RolePermissionController::class, 'index'])->middleware('permission:settings,view')->name('index');
            Route::put('/', [\App\Http\Controllers\RolePermissionController::class, 'update'])->middleware('permission:settings,edit')->name('update');
            Route::post('/reset', [\App\Http\Controllers\RolePermissionController::class, 'reset'])->middleware('permission:settings,edit')->name('reset');
        });

        Route::get('/device', function () {
            return view('device');
        })->middleware('permission:settings,view');
        Route::get('/integration', function () {
            return view('integration');
        })->middleware('permission:settings,view');
    });


    Route::get('/verify/{code}', [CertificateController::class, 'verify']);
    // USERNAME CHECK (for user management)
    Route::get('/check-username', function (Request $request) {
        $username = $request->query('username');
        $userId = $request->query('user_id');
        $query = User::where('username', $username);
        if ($userId) {
            $query->where('user_id', '!=', $userId);
        }
        return response()->json(['available' => !$query->exists()]);
    })->middleware('permission:users,view')->name('check.username');

    Route::prefix('templates')->name('templates.')->group(function () {

    // ── List all templates
    Route::get('/',          [TemplateController::class, 'index'])   ->name('index');

    // ── Create form + store
    Route::get('/create',    [TemplateController::class, 'create'])  ->name('create');
    Route::post('/',         [TemplateController::class, 'store'])   ->name('store');

    // ── Canvas editor
    Route::get('/{id}/editor',      [TemplateController::class, 'editor'])      ->name('editor');

    // ── Save layout (Ajax POST from editor)
    Route::post('/{id}/save-layout', [TemplateController::class, 'saveLayout']) ->name('saveLayout');

    // ── Preview (web view or JSON if ?Accept=application/json)
    Route::get('/{id}/preview',     [TemplateController::class, 'preview'])     ->name('preview');

    // ── Clone / duplicate
    Route::post('/{id}/clone',      [TemplateController::class, 'clone'])       ->name('clone');

    // ── Upload image asset for a template's canvas
    Route::post('/{id}/upload-image', [TemplateController::class, 'uploadImage'])->name('uploadImage');

    // ── Delete
    Route::delete('/{id}',          [TemplateController::class, 'destroy'])     ->name('destroy');

});

});


