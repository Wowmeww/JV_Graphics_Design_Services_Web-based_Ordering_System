<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/message/{receiver?}', [MessageController::class, 'index'])->name('message.index');
    Route::post('/message/{receiver}', [MessageController::class, 'store'])->name('message.store');
});
