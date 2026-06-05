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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\ConsultationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Development Self-Healing Migration for Doctor Tables
Route::get('/doctor/dev-migrate', [DoctorController::class, 'devMigrate'])->name('doctor.devMigrate');

// Admin Routes (Independent Session Auth)
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::post('/admin', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::post('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggleStatus');
Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
Route::post('/admin/doctors/save', [AdminController::class, 'saveDoctor'])->name('admin.doctors.save');
Route::post('/admin/doctors/{id}/toggle-status', [AdminController::class, 'toggleDoctorStatus'])->name('admin.doctors.toggleStatus');
Route::delete('/admin/doctors/{id}', [AdminController::class, 'deleteDoctor'])->name('admin.doctors.delete');

// Doctor Routes (Independent Session Auth)
Route::get('/doctor/register', [DoctorController::class, 'showRegister'])->name('doctor.register');
Route::post('/doctor/register', [DoctorController::class, 'register'])->name('doctor.register.submit');
Route::get('/doctor/login', [DoctorController::class, 'showLogin'])->name('doctor.login');
Route::post('/doctor/login', [DoctorController::class, 'login'])->name('doctor.login.submit');
Route::post('/doctor/logout', [DoctorController::class, 'logout'])->name('doctor.logout');
Route::get('/doctor/dashboard', [DoctorController::class, 'index'])->name('doctor.dashboard');
Route::post('/doctor/profile', [DoctorController::class, 'updateProfile'])->name('doctor.profile.update');
Route::post('/doctor/appointments/{id}/status', [DoctorController::class, 'toggleAppointmentStatus'])->name('doctor.appointments.status');
Route::post('/doctor/recommendations/save', [DoctorController::class, 'saveRecommendation'])->name('doctor.recommendations.save');

// Medical Records Routes
Route::post('/doctor/medical-records/save', [MedicalRecordController::class, 'saveRecord'])->name('doctor.medicalRecords.save');
Route::get('/doctor/medical-records/history/{userId}', [MedicalRecordController::class, 'getHistory'])->name('doctor.medicalRecords.history');

// Consultations Routes
Route::get('/doctor/consultations/chat/{userId}', [ConsultationController::class, 'getMessages'])->name('doctor.consultations.messages');
Route::post('/doctor/consultations/send', [ConsultationController::class, 'sendMessage'])->name('doctor.consultations.send');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/health', [HealthController::class, 'index'])->name('health');
    Route::post('/health/update', [HealthController::class, 'update'])->name('health.update');
    Route::post('/health/qr-token', [HealthController::class, 'generateQrToken'])->name('health.qr.token');
    Route::get('/health/qr-poll', [HealthController::class, 'pollQrData'])->name('health.qr.poll');

    Route::get('/workout', [WorkoutController::class, 'index'])->name('workout');
    Route::post('/workout/log', [WorkoutController::class, 'logWorkout'])->name('workout.log');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
    Route::post('/appointments/book', [AppointmentController::class, 'store'])->name('appointments.book');
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');
    Route::get('/chatbot/doctors', [ConsultationController::class, 'getDoctorList'])->name('chatbot.doctors');
    Route::get('/chatbot/messages/{doctorId}', [ConsultationController::class, 'getPatientMessages'])->name('chatbot.doctor.messages');
    Route::post('/chatbot/send-doctor', [ConsultationController::class, 'sendPatientMessage'])->name('chatbot.doctor.send');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::get('/menu/ai-data', [MenuController::class, 'aiRecommendation'])->name('menu.ai.data');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Feedback/Review Routes
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::post('/feedback/{feedback}/reply', [FeedbackController::class, 'reply'])->name('feedback.reply');
    Route::post('/feedback/{feedback}/react', [FeedbackController::class, 'react'])->name('feedback.react');
    Route::post('/admin/feedback/{feedback}/reply', [FeedbackController::class, 'adminReply'])->name('admin.feedback.reply');
    Route::post('/admin/feedback/{feedback}/react', [FeedbackController::class, 'adminReact'])->name('admin.feedback.react');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // MealPlan Routes
    Route::get('/meal-plans', [\App\Http\Controllers\MealPlanController::class, 'index'])->name('mealplans.index');
    Route::get('/meal-plans/{id}', [\App\Http\Controllers\MealPlanController::class, 'show'])->name('mealplans.show');
    Route::post('/meal-plans', [\App\Http\Controllers\MealPlanController::class, 'store'])->name('mealplans.store');
    Route::post('/meal-plans/{id}/assign', [\App\Http\Controllers\MealPlanController::class, 'assignToPatient'])->name('mealplans.assign');
});

// Mobile QR pairing - public, token-based security
Route::get('/pair/{token}', [HealthController::class, 'pairPage'])->name('health.pair.page');
Route::post('/pair/{token}', [HealthController::class, 'pairSubmit'])->name('health.pair.submit');

require __DIR__.'/auth.php';
