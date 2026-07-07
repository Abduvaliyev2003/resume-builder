<?php

use Illuminate\Support\Facades\Route;
use App\Domains\User\Controllers\AuthController;
use App\Domains\Template\Controllers\TemplateController;
use App\Domains\Resume\Controllers\ResumeController;
use App\Domains\AI\Controllers\AIController;
use App\Domains\File\Controllers\FileController;
use App\Domains\Analytics\Controllers\AnalyticsController;

// Public Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/telegram/login', [AuthController::class, 'telegramLogin']);
// Public Template routes
Route::get('/templates', [TemplateController::class, 'index']);
Route::get('/templates/{id}', [TemplateController::class, 'show']);

// Public Download route (without prefix if needed, but in api group it will have /api)
Route::get('/downloads/{token}', [FileController::class, 'download'])->name('resumes.download');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/email/verify-status', [AuthController::class, 'getVerificationStatus']);
    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationNotification'])->middleware('throttle:6,1');
    Route::post('/email/verify-code', [AuthController::class, 'verifyCode']);


    Route::post('/telegram/logout', [AuthController::class, 'telegramLogout']);

    Route::get('/telegram/me', [AuthController::class, 'telegramMe']);

    // Resumes CRUD & Duplication
    Route::get('/resumes', [ResumeController::class, 'index'])->middleware('verified');
    Route::post('/resumes', [ResumeController::class, 'store'])->middleware('verified');
    Route::get('/resumes/{id}', [ResumeController::class, 'show'])->middleware('verified');
    Route::put('/resumes/{id}', [ResumeController::class, 'update'])->middleware('verified');
    Route::delete('/resumes/{id}', [ResumeController::class, 'destroy'])->middleware('verified');
    Route::post('/resumes/{id}/duplicate', [ResumeController::class, 'duplicate'])->middleware('verified');

    // AI Features
    Route::post('/resumes/{id}/grammar-check', [AIController::class, 'grammarCheck'])->middleware('verified');
    Route::post('/resumes/{id}/ats-analyze', [AIController::class, 'atsAnalyze'])->middleware('verified');
    Route::post('/resumes/{id}/missing-sections', [AIController::class, 'missingSections'])->middleware('verified');
    Route::post('/resumes/{id}/job-match', [AIController::class, 'jobMatch'])->middleware('verified');
    Route::get('/resumes/{id}/ai-reviews', [AIController::class, 'history'])->middleware('verified');

    // Exporting
    Route::post('/resumes/{id}/export', [FileController::class, 'export'])->middleware('verified');

    // Analytics
    Route::get('/analytics/stats', [AnalyticsController::class, 'stats']);
    Route::get('/analytics/logs', [AnalyticsController::class, 'logs']);
});
