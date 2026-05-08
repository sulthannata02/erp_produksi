<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\QcController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\TrackingController;

// ─── Guest only ───
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Semua user yang sudah login ───
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));

    // Dashboard (controller yang tentukan tampilan berdasarkan role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Admin only ──────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::resource('materials', MaterialController::class);
        Route::resource('productions', ProductionController::class);
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    });

    // ── Operator only ────────────────────────────────────────
    Route::middleware('role:operator')->group(function () {
        Route::resource('qcs', QcController::class);
        Route::resource('packings', PackingController::class);
        Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
    });
});
