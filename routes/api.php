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

    // Resumes CRUD & Duplication
    Route::get('/resumes', [ResumeController::class, 'index']);
    Route::post('/resumes', [ResumeController::class, 'store']);
    Route::get('/resumes/{id}', [ResumeController::class, 'show']);
    Route::put('/resumes/{id}', [ResumeController::class, 'update']);
    Route::delete('/resumes/{id}', [ResumeController::class, 'destroy']);
    Route::post('/resumes/{id}/duplicate', [ResumeController::class, 'duplicate']);

    // AI Features
    Route::post('/resumes/{id}/grammar-check', [AIController::class, 'grammarCheck']);
    Route::post('/resumes/{id}/ats-analyze', [AIController::class, 'atsAnalyze']);
    Route::post('/resumes/{id}/missing-sections', [AIController::class, 'missingSections']);
    Route::post('/resumes/{id}/job-match', [AIController::class, 'jobMatch']);
    Route::get('/resumes/{id}/ai-reviews', [AIController::class, 'history']);

    // Exporting
    Route::post('/resumes/{id}/export', [FileController::class, 'export']);

    // Analytics
    Route::get('/analytics/stats', [AnalyticsController::class, 'stats']);
    Route::get('/analytics/logs', [AnalyticsController::class, 'logs']);
});
