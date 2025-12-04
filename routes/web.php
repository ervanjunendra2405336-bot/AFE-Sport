<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Lapangan
Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');
Route::get('/lapangan/{id}', [LapanganController::class, 'show'])->name('lapangan.show');
Route::get('/category/{slug}', [LapanganController::class, 'byCategory'])->name('lapangan.category');

// Booking
Route::get('/booking/{lapangan_id}', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking-payment/{id}', [BookingController::class, 'payment'])->name('booking.payment');
Route::post('/booking-payment/{id}', [BookingController::class, 'processPayment'])->name('booking.payment.process');
Route::get('/booking-success/{id}', [BookingController::class, 'success'])->name('booking.success');
Route::post('/check-availability', [BookingController::class, 'checkAvailability'])->name('booking.check');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // Lapangan Management
    Route::resource('lapangan', App\Http\Controllers\Admin\LapanganController::class)
        ->names([
            'index' => 'admin.lapangan.index',
            'create' => 'admin.lapangan.create',
            'store' => 'admin.lapangan.store',
            'edit' => 'admin.lapangan.edit',
            'update' => 'admin.lapangan.update',
            'destroy' => 'admin.lapangan.destroy',
        ]);

    // Booking Management
    Route::get('/booking', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('admin.booking.index');
    Route::get('/booking/verifikasi', [App\Http\Controllers\Admin\BookingController::class, 'verifikasi'])->name('admin.booking.verifikasi');
    Route::post('/booking/{booking}/approve', [App\Http\Controllers\Admin\BookingController::class, 'approve'])->name('admin.booking.approve');
    Route::post('/booking/{booking}/reject', [App\Http\Controllers\Admin\BookingController::class, 'reject'])->name('admin.booking.reject');

    // Kasir - Walk-in Booking System
    Route::prefix('kasir')->name('admin.kasir.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\KasirController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\KasirController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\KasirController::class, 'store'])->name('store');
        Route::get('/{booking}', [App\Http\Controllers\Admin\KasirController::class, 'show'])->name('show');
        Route::post('/{booking}/confirm', [App\Http\Controllers\Admin\KasirController::class, 'confirm'])->name('confirm');
        Route::post('/check-slots', [App\Http\Controllers\Admin\KasirController::class, 'getAvailableSlots'])->name('check-slots');
    });

    // Old Kasir Routes (kept for backward compatibility)
    Route::get('/booking/kasir', [App\Http\Controllers\Admin\BookingController::class, 'kasir'])->name('admin.booking.kasir');
    Route::post('/booking/{booking}/confirm-payment', [App\Http\Controllers\Admin\BookingController::class, 'confirmPayment'])->name('admin.booking.confirm-payment');
    Route::post('/booking/scan', [App\Http\Controllers\Admin\BookingController::class, 'scanBooking'])->name('admin.booking.scan');
    Route::put('/booking/{booking}/cancel', [App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('admin.booking.cancel');

    // Transaksi
    Route::get('/transaksi', [App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('admin.transaksi');

    // Analitik
    Route::get('/analitik', [App\Http\Controllers\Admin\AnalitikController::class, 'index'])->name('admin.analitik');
});
