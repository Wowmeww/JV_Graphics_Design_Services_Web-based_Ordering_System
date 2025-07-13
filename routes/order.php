<?php


use App\Http\Controllers\ManageOrderController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'password.confirm', 'verified', 'can:create,App\Models\Product'])->group(function () {
    Route::get('/manage/orders', [ManageOrderController::class, 'index'])->name('manage.orders.index');
    Route::get('/manage/orders/{order}', [ManageOrderController::class, 'show'])->name('manage.orders.show');
    // Route::get('/manage/orders/{order}/edit', [ManageOrderController::class, 'edit'])->name('manage.orders.edit');
    Route::patch('/manage/orders/{order}', [ManageOrderController::class, 'update'])->name('manage.orders.update');
});
