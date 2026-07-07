<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Domains\User\Controllers\AuthController;
use App\Domains\Profile\Controllers\ProfileController;

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
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [FrontendController::class, 'verifyEmailNotice'])->name('verification.notice');
    Route::post('/email/verify', [FrontendController::class, 'verifyEmailCode'])->name('verification.verify');
    Route::post('/email/verification-notification', [FrontendController::class, 'sendVerificationNotification'])->middleware('throttle:6,1')->name('verification.send');

    Route::get('/dashboard', [FrontendController::class, 'dashboard'])->name('dashboard')->middleware('verified');
    Route::get('/templates', [FrontendController::class, 'templates'])->name('templates')->middleware('verified');
    Route::get('/resumes/{id}/builder', [FrontendController::class, 'builder'])->name('resumes.builder')->middleware('verified');
    Route::get('/resumes/{id}/preview', [FrontendController::class, 'preview'])->name('resumes.preview')->middleware('verified');
    Route::get('/resumes/{id}/ai-feedback', [FrontendController::class, 'aiFeedback'])->name('resumes.ai-feedback')->middleware('verified');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile routes
    Route::middleware('verified')->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::post('/update', [ProfileController::class, 'updateProfile'])->name('update');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::post('/settings', [ProfileController::class, 'updateSettings'])->name('settings');
        Route::post('/logout-other-devices', [ProfileController::class, 'logoutOtherDevices'])->name('logout-other-devices');
        Route::delete('/delete', [ProfileController::class, 'deleteAccount'])->name('delete');
    });
});

