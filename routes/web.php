<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root ke dashboard jika login, atau ke login jika belum
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Autentikasi Tamu
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Area Terproteksi Autentikasi
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Surat (Akses Membaca & Preview untuk Semua Peran yang Sah)
    Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/create', [SuratController::class, 'create'])
        ->middleware('role:staf,admin')
        ->name('surat.create');
    Route::post('/surat', [SuratController::class, 'store'])
        ->middleware('role:staf,admin')
        ->name('surat.store');
    Route::get('/surat/{surat}', [SuratController::class, 'show'])->name('surat.show');
    Route::get('/surat/{surat}/stream', [SuratController::class, 'streamPdf'])->name('surat.stream');
    Route::get('/surat/{surat}/download', [SuratController::class, 'downloadPdf'])->name('surat.download');
    Route::get('/surat/{surat}/edit', [SuratController::class, 'edit'])
        ->middleware('role:staf,admin')
        ->name('surat.edit');
    Route::put('/surat/{surat}', [SuratController::class, 'update'])
        ->middleware('role:staf,admin')
        ->name('surat.update');
    Route::delete('/surat/{surat}', [SuratController::class, 'destroy'])
        ->middleware('role:staf,admin')
        ->name('surat.destroy');

    // Riwayat Aktivitas & Audit Trail (Khusus Admin Sistem)
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->middleware('role:admin')
        ->name('activity_logs.index');

    // Manajemen Pengguna (Khusus Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle_status');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
