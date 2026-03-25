<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadImportExportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\LeadAllocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Illuminate\Support\Facades\Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/setup-godaddy', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return "Setup successfully completed on GoDaddy! Migrations run, cache cleared, storage linked.";
    } catch (\Exception $e) {
        return "Error running setup: " . $e->getMessage() . "<br>Please ensure your database credentials in .env are correct.";
    }
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Leads creation open to all authenticated users
    Route::get('leads/create', [LeadController::class, 'create'])->name('leads.create');
    Route::post('leads', [LeadController::class, 'store'])->name('leads.store');

    // Lead Management (Calling Team & Admins)
    Route::middleware('role:admin,manager,calling')->group(function () {
        Route::resource('leads', LeadController::class)->except(['create', 'store']);
        Route::post('leads/{lead}/quick-update', [LeadController::class, 'quickUpdate'])->name('leads.quickUpdate');
        Route::post('leads/{lead}/followups', [LeadController::class, 'storeFollowUp'])->name('leads.followups.store');
        Route::post('leads/import', [LeadImportExportController::class, 'import'])->name('leads.import');
        Route::get('leads/export/download', [LeadImportExportController::class, 'export'])->name('leads.export');
    });

    // Tasks (Technical Team & Admins)
    Route::middleware('role:admin,manager,technical')->group(function () {
        Route::resource('tasks', TaskController::class);
    });

    // Attendance & Leaves (All Users)
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
    Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');
    Route::resource('leaves', LeaveRequestController::class);

    // Admin Specific
    Route::middleware('role:admin,manager')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');
        Route::get('users/{user}/attendance/{date}/lead-stats', [UserController::class, 'leadStats'])->name('users.attendance.leadStats');
        Route::resource('roles', RoleController::class);
        Route::resource('leaves', LeaveRequestController::class);

        Route::patch('leaves/{leave}/status', [LeaveRequestController::class, 'updateStatus'])->name('leaves.updateStatus');

        // Lead Allocation
        Route::get('leads/allocation', [LeadAllocationController::class, 'index'])->name('leads.allocation');
        Route::post('leads/allocate', [LeadAllocationController::class, 'allocate'])->name('leads.allocate');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/user/{user}', [ReportController::class, 'userPerformance'])->name('reports.user.performance');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('/reports/export/leads', [ReportController::class, 'exportLeads'])->name('reports.export.leads');
        Route::get('/reports/export/tasks', [ReportController::class, 'exportTasks'])->name('reports.export.tasks');
    });
});

require __DIR__ . '/auth.php';
