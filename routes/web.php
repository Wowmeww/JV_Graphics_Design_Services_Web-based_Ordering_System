<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::inertia('/dashboard', 'Dashboard')->name('dashboard');

require __DIR__ . '/products.php';


// Sign in with Google and Facebook

Route::get('/auth/google/redirect', function (Request $request) {
    return Socialite::driver("google")->redirect();
})->name('google.rediret');

Route::get('/auth/google/callback', function (Request $request) {
    $googleUser = Socialite::driver("google")->user();
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
