<?php

use App\Http\Controllers\CustomizeProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductOptionController;

Route::middleware(['auth', 'verified', 'can:create,App\Models\Product'])->group(function () {

    Route::get('/customize/create', [CustomizeProductController::class, 'create'])->name('customize.create');
    Route::get('/customize/{product}/edit', [CustomizeProductController::class, 'edit'])->name('customize.edit');


    // 🎨 Product Option Routes
    Route::prefix('products/{product}/options')->name('product.option.')->group(function () {
        Route::get('/create', [ProductOptionController::class, 'create'])
            ->name('create');

        Route::get('/{option}/show', [ProductOptionController::class, 'show'])->name('show');
        Route::get('/{option}/edit', [ProductOptionController::class, 'edit'])->name('edit');

        Route::post('/', [ProductOptionController::class, 'store'])
            ->name('store');

        Route::patch('/{option}/update', [ProductOptionController::class, 'update'])
            ->name('update');

        Route::delete('/{option}', [ProductOptionController::class, 'destroy'])
            ->name('destroy');
    });
    // 📦 Product Routes
    Route::prefix('products')->name('product.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::get('/{product}/show', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');

        Route::post('/', [ProductController::class, 'store'])
            ->name('store');

        Route::patch('/{product}/update', [ProductController::class, 'update'])
            ->name('update');

        Route::delete('/{product}', [ProductController::class, 'destroy'])
            ->name('destroy');
    });
});
