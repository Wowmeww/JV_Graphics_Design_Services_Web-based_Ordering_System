<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');



require __DIR__ . '/shop.php';
require __DIR__ . '/products.php';


// Sign in with Google and Facebook

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/auth/facebook', [GoogleController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('/auth/facebook/callback', [GoogleController::class, 'handleFacebookCallback']);


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
