<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MedicalRecordController;

// Public routes
Route::get('/health', function() {
    return response()->json(['status' => 'API is working']);
});

// Protected routes (require auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/user/profile', function (Request $request) {
        return response()->json($request->user());
    });

    // Health Metrics
    Route::get('/health/metrics', [HealthController::class, 'getMetrics']);
    Route::post('/health/update', [HealthController::class, 'update']);
    Route::get('/health/history', [HealthController::class, 'getHistory']);
    
    // Workouts
    Route::get('/workouts', [WorkoutController::class, 'index']);
    Route::post('/workouts/log', [WorkoutController::class, 'logWorkout']);
    Route::get('/workouts/statistics', [WorkoutController::class, 'getStatistics']);
    
    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments/book', [AppointmentController::class, 'store']);
    Route::post('/appointments/{id}/reschedule', [AppointmentController::class, 'reschedule']);
    Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
    
    // Meal Plans
    Route::get('/meal-plans', [MealPlanController::class, 'index']);
    Route::get('/meal-plans/{id}', [MealPlanController::class, 'show']);
    Route::post('/meal-plans', [MealPlanController::class, 'store']);
    Route::post('/meal-plans/{id}/assign', [MealPlanController::class, 'assignToPatient']);
    
    // Consultations
    Route::get('/consultations/doctors', [ConsultationController::class, 'getDoctorList']);
    Route::get('/consultations/{doctorId}/messages', [ConsultationController::class, 'getPatientMessages']);
    Route::post('/consultations/send', [ConsultationController::class, 'sendPatientMessage']);
    Route::get('/consultations/inbox', [ConsultationController::class, 'getUserInbox']);
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    
    // Feedback
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::post('/feedback/{id}/reply', [FeedbackController::class, 'reply']);
    Route::post('/feedback/{id}/react', [FeedbackController::class, 'react']);
    
    // Medical Records (for authenticated users)
    Route::get('/medical-records', [MedicalRecordController::class, 'index']);
});

// Doctor routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('doctor')->group(function () {
        // Medical Records
        Route::post('/medical-records/{userId}/save', [MedicalRecordController::class, 'saveRecord']);
        Route::get('/medical-records/{userId}/history', [MedicalRecordController::class, 'getHistory']);
        
        // Consultations for doctors
        Route::get('/consultations/patients/{userId}/messages', [ConsultationController::class, 'getMessages']);
        Route::post('/consultations/send', [ConsultationController::class, 'sendMessage']);
        Route::get('/consultations/unread-summary', [ConsultationController::class, 'getUnreadSummary']);
        
        // Meal Plans
        Route::post('/meal-plans', [MealPlanController::class, 'store']);
        Route::post('/meal-plans/{id}/assign', [MealPlanController::class, 'assignToPatient']);
        
        // Patient Health History
        Route::get('/patients/{userId}/health-history', [HealthController::class, 'getPatientHealthHistory']);
    });
});

