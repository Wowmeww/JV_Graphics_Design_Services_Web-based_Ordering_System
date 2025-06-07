<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\HomeController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');




// Sign in with Google and Facebook

Route::get('/auth/google/redirect', function (Request $request) {
    return Socialite::driver("google")->redirect();
})->name('google.rediret');

Route::get('/auth/google/callback', function (Request $request) {
    $googleUser = Socialite::driver("google")->user();
    dd($googleUser);
    // $user = User::updateOrCreate([
    //     'google_id' => $googleUser->id,
    //     [
    //         'name' => $googleUser->name,
    //         'email' => $googleUser->email,
    //         'password' => Str::password(12)
    //     ]

    // ]);

    // dd($user);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
