<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'can:create,App\Models\Product', 'password.confirm'])->group(function () {
    Route::get('/manage/users', [UserController::class, 'index'])->name('manage.users.index');
    Route::get('/manage/users/{user}', [UserController::class, 'edit'])->name('manage.users.edit');
    Route::patch('/manage/users/{user}/update', [UserController::class, 'update'])->name('manage.users.update');
});
