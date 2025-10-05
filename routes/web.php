<?php

use App\Http\Controllers\HomeController;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');
Route::get('/about', [HomeController::class, 'about'])->name('about');

require __DIR__ . '/shop.php';
require __DIR__ . '/products.php';

// Sign in with Google and Facebook
// Route::middleware(['guest'])->group(function () {

//     Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
//     Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

//     Route::get('/auth/facebook', [GoogleController::class, 'redirectToFacebook'])->name('facebook.login');
//     Route::get('/auth/facebook/callback', [GoogleController::class, 'handleFacebookCallback']);
// });


require __DIR__ . '/auth.php';
require __DIR__ . '/message.php';
require __DIR__ . '/order.php';
require __DIR__ . '/announcement.php';
require __DIR__ . '/designer.php';
require __DIR__ . '/transaction.php';
require __DIR__ . '/user.php';



// GET THE WEB APP NAME

Route::get('/page-settings/{what}', function (string $what) {
    $res = '';
    $value = SystemSetting::where('key', $what)->first()->value;
    switch ($what) {
        case 'app_name':
            $res = $value;
            break;
        case 'app_logo':
            $res = $value ? "/storage/$value" : '/favicon.jpg';
            break;
    }
    return $res;
})->name('page.settings');
