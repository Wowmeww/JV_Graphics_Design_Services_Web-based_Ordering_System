<?php


use App\Http\Controllers\ManageOrderController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified', 'can:create,App\Models\Product'])->group(function () {
    Route::get('/manage/orders', [ManageOrderController::class, 'index'])->name('manage.orders.index');
    Route::get('/manage/orders/{order}', [ManageOrderController::class, 'show'])
        ->middleware('password.confirm')
        ->name('manage.orders.show');
    Route::patch('/manage/orders/{order}', [ManageOrderController::class, 'update'])
        ->middleware('password.confirm')
        ->name('manage.orders.update');
});
