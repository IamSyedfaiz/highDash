<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadImportExportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\LeadAllocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Lead Management
    Route::resource('leads', LeadController::class);
    Route::post('leads/{lead}/quick-update', [LeadController::class, 'quickUpdate'])->name('leads.quickUpdate');
    Route::post('leads/import', [LeadImportExportController::class, 'import'])->name('leads.import');
    Route::get('leads/export/download', [LeadImportExportController::class, 'export'])->name('leads.export');

    // Attendance & Leaves
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::resource('leaves', LeaveRequestController::class);

    // Admin Specific
    Route::middleware('role:admin,manager')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::patch('leaves/{leave}/status', [LeaveRequestController::class, 'updateStatus'])->name('leaves.updateStatus');

        // Lead Allocation
        Route::get('leads/allocation', [LeadAllocationController::class, 'index'])->name('leads.allocation');
        Route::post('leads/allocate', [LeadAllocationController::class, 'allocate'])->name('leads.allocate');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    });
});

require __DIR__ . '/auth.php';
