<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Operator\DashboardController as OperatorDashboard;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\ProductionController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Operator\QcController;
use App\Http\Controllers\Operator\PackingController;
use App\Http\Controllers\Operator\TrackingController;
use App\Http\Controllers\Operator\MaterialMasukController;
use App\Http\Controllers\Operator\ProductionWorkController;

// ─── Guest only ───
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Semua user yang sudah login ───
Route::middleware('auth')->group(function () {
    Route::post('/notifications/read-all', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');

    Route::get('/', fn() => redirect()->route('dashboard'));

    // Dashboard (controller yang tentukan tampilan berdasarkan role)
    Route::get('/dashboard', function() {
        if (auth()->user()->role === 'operator') {
            return app(OperatorDashboard::class)->index();
        }
        return app(AdminDashboard::class)->index();
    })->name('dashboard');

    // ── Admin only ──────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        // ── Reports ───────────────────────────────────────────
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('laporan.index');
        Route::get('/reports/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'pdf'])->name('laporan.pdf');

        Route::resource('materials', MaterialController::class);
        Route::resource('users', UserController::class);
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    });

    // ── Shared (Admin & Operator) ───────────────────────────
    Route::get('/productions/{production}/status', [ProductionController::class, 'status'])->name('productions.status');
    Route::put('/productions/{production}/status', [ProductionController::class, 'updateStatus'])->name('productions.updateStatus');

    // ── Operator only ────────────────────────────────────────
    Route::middleware('role:operator')->group(function () {
        Route::resource('material-masuks', MaterialMasukController::class);
        Route::resource('productions', ProductionController::class);
        Route::get('/packings/{packing}/print', [PackingController::class, 'print'])->name('packings.print');
        Route::resource('qcs', QcController::class);
        Route::resource('packings', PackingController::class);
        Route::get('/production-work', [ProductionWorkController::class, 'index'])->name('production-work.index');
        Route::get('/production-work/{id}/start', [ProductionWorkController::class, 'showStart'])->name('production-work.showStart');
        Route::post('/production-work/{id}/start', [ProductionWorkController::class, 'start'])->name('production-work.start');
        
        Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
    });
});
