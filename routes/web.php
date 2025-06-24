<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::inertia('/dashboard', 'Dashboard')->name('dashboard');

require __DIR__ . '/products.php';


// Sign in with Google and Facebook

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
