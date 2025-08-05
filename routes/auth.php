<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // REGISTER ROUTES
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    // LOGIN ROUTES
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'passRequest'])->name('password.request');

    Route::post('/forgot-password', [PasswordResetController::class, 'passEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'passReset'])->name('password.reset');

    Route::post('/reset-password', [PasswordResetController::class, 'passUpdate'])->name('password.update');
});


// EMAIL VERIFICATION ROUTES


Route::middleware(['auth'])->group(function () {
    Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    Route::get('/email/verify', [AuthenticatedSessionController::class, 'verificationNotice'])
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthenticatedSessionController::class, 'verificationVerify'])
        ->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthenticatedSessionController::class, 'verificationSend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    // PASSWORD CONFIRMATION ROUTES
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
});

// PROFILE SETTINGS ROUTE

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->middleware(['auth', 'verified'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password/edit', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::get('/appearance/edit', [ProfileController::class, 'editAppearance'])->name('appearance.edit');
});



// NOTIFICATION ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/mark_all_as_read', [NotificationController::class, 'markAllRead'])->name('notification.mark.all.read');
    Route::patch('/notification/{notification}/update', [NotificationController::class, 'update'])->name('notification.update');
    Route::get('/notifications/index', [NotificationController::class, 'index'])->name('notification.index');
    Route::get('/notifications/{notification}/show', [NotificationController::class, 'show'])->name('notification.show');
});
