<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('landing');
});

// Authentication Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// OTP & LUPA PASSWORD ROUTES
Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('forgot-password', [AuthController::class, 'generateOtp'])->name('password.email');
Route::get('otp-mockup', [AuthController::class, 'showOtpMockup'])->name('otp.mockup');
Route::get('verify-otp', [AuthController::class, 'showVerifyOtp'])->name('otp.verify');
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.check');
Route::get('reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Siswa Routes
Route::middleware(['auth', 'role:siswa', 'autocutoff'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('dashboard', [SiswaController::class, 'index'])->name('dashboard');
    Route::get('pendaftaran', [SiswaController::class, 'pendaftaran'])->name('pendaftaran');
    Route::get('checkout/{id}', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('riwayat', [SiswaController::class, 'riwayat'])->name('riwayat');
    Route::get('kelas-saya', [SiswaController::class, 'kelasSaya'])->name('kelas_saya');
    Route::get('bantuan', [SiswaController::class, 'bantuan'])->name('bantuan');
});

// Admin Routes
Route::middleware(['auth', 'role:admin', 'autocutoff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Pengajar
    Route::get('pengajar', [AdminController::class, 'pengajarIndex'])->name('pengajar.index');
    Route::post('pengajar', [AdminController::class, 'pengajarStore'])->name('pengajar.store');
    Route::post('pengajar/{id}/update', [AdminController::class, 'pengajarUpdate'])->name('pengajar.update');
    Route::get('pengajar/{id}/delete', [AdminController::class, 'pengajarDestroy'])->name('pengajar.destroy');

    // Kelas
    Route::get('kelas', [AdminController::class, 'kelasIndex'])->name('kelas.index');
    Route::post('kelas', [AdminController::class, 'kelasStore'])->name('kelas.store');
    Route::post('kelas/{id}/update', [AdminController::class, 'kelasUpdate'])->name('kelas.update');
    Route::get('kelas/{id}/delete', [AdminController::class, 'kelasDestroy'])->name('kelas.destroy');

    // Jadwal
    Route::get('jadwal', [AdminController::class, 'jadwalIndex'])->name('jadwal.index');
    Route::post('jadwal', [AdminController::class, 'jadwalStore'])->name('jadwal.store');
    Route::post('jadwal/{id}/update', [AdminController::class, 'jadwalUpdate'])->name('jadwal.update');
    Route::get('jadwal/{id}/delete', [AdminController::class, 'jadwalDestroy'])->name('jadwal.destroy');

    // Pembayaran
    Route::get('pembayaran', [AdminController::class, 'pembayaranIndex'])->name('pembayaran.index');
    Route::post('pembayaran/{id}/status', [AdminController::class, 'updatePembayaranStatus'])->name('pembayaran.update');

    // Siswa
    Route::get('siswa', [AdminController::class, 'siswaIndex'])->name('siswa.index');

    // Pengaturan
    Route::get('pengaturan', [AdminController::class, 'pengaturanIndex'])->name('pengaturan.index');
    Route::post('pengaturan/profile', [AdminController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::post('pengaturan/password', [AdminController::class, 'updatePassword'])->name('pengaturan.password');
});

// Midtrans Webhook (Bypass CSRF di file app/Http/Middleware/VerifyCsrfToken.php atau bootstrap/app.php nantinya)
Route::post('payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
