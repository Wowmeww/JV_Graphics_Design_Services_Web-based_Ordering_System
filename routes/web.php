<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

require __DIR__ . '/shop.php';
require __DIR__ . '/products.php';

// EMAIL VERIFICATION ROUTES


Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [AuthenticatedSessionController::class, 'verificationNotice'])
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthenticatedSessionController::class, 'verificationVerify'])
        ->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthenticatedSessionController::class, 'verificationSend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});



// Sign in with Google and Facebook

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/auth/facebook', [GoogleController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('/auth/facebook/callback', [GoogleController::class, 'handleFacebookCallback']);


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
