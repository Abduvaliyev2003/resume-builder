<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Domains\User\Controllers\AuthController;

// Public shared view (no auth needed)
Route::get('/resumes/shared/{id}', [FrontendController::class, 'shared'])->name('resumes.shared');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    Route::get('/login', [FrontendController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [FrontendController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/forgot-password', [FrontendController::class, 'forgotPassword'])->name('password.request');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [FrontendController::class, 'dashboard'])->name('dashboard');
    Route::get('/templates', [FrontendController::class, 'templates'])->name('templates');
    Route::get('/resumes/{id}/builder', [FrontendController::class, 'builder'])->name('resumes.builder');
    Route::get('/resumes/{id}/preview', [FrontendController::class, 'preview'])->name('resumes.preview');
    Route::get('/resumes/{id}/ai-feedback', [FrontendController::class, 'aiFeedback'])->name('resumes.ai-feedback');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
