<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductOptionController;


Route::middleware(['auth', 'verified', 'can:create,App\Models\Product', 'password.confirm'])->group(function () {

    // Product Routes
    Route::resource('products', ProductController::class)->names('product');

    // Nested Product Option Routes
    Route::prefix('products/{product}')->group(function () {
        Route::get('options/create', [ProductOptionController::class, 'create'])
            ->name('option.create')
            ->middleware(['can:createVariant,product']);
        Route::post('options', [ProductOptionController::class, 'store'])->name('option.store');
        Route::get('options/{option}', [ProductOptionController::class, 'show'])->name('option.show');
        Route::get('options/{option}/edit', [ProductOptionController::class, 'edit'])->name('option.edit');
        Route::patch('options/{option}', [ProductOptionController::class, 'update'])->name('option.update');
        Route::delete('options/{option}', [ProductOptionController::class, 'destroy'])->name('option.destroy');
    });
});
