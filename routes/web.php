<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PricingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/health', [HealthController::class, 'index'])->name('health');
    Route::post('/health/update', [HealthController::class, 'update'])->name('health.update');
    Route::post('/health/qr-token', [HealthController::class, 'generateQrToken'])->name('health.qr.token');
    Route::get('/health/qr-poll', [HealthController::class, 'pollQrData'])->name('health.qr.poll');

    Route::get('/workout', [WorkoutController::class, 'index'])->name('workout');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Mobile QR pairing - public, token-based security
Route::get('/pair/{token}', [HealthController::class, 'pairPage'])->name('health.pair.page');
Route::post('/pair/{token}', [HealthController::class, 'pairSubmit'])->name('health.pair.submit');

require __DIR__.'/auth.php';
