<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductOptionController;

Route::middleware(['auth', 'verified', 'can:create,App\Models\Product'])->group(function () {

    // 📦 Product Routes
    Route::prefix('products')->name('product.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');

        Route::post('/', [ProductController::class, 'store'])
            ->middleware('password.confirm')
            ->name('store');

        Route::match(['put', 'patch'], '/{product}', [ProductController::class, 'update'])
            ->middleware('password.confirm')
            ->name('update');

        Route::delete('/{product}', [ProductController::class, 'destroy'])
            ->middleware('password.confirm')
            ->name('destroy');

        // 🎨 Nested Product Option Routes
        Route::prefix('/{product}/options')->name('option.')->group(function () {
            Route::get('/create', [ProductOptionController::class, 'create'])
                ->middleware('can:createVariant,product')
                ->name('create');

            Route::get('/{option}', [ProductOptionController::class, 'show'])->name('show');
            Route::get('/{option}/edit', [ProductOptionController::class, 'edit'])->name('edit');

            Route::post('/', [ProductOptionController::class, 'store'])
                ->middleware('password.confirm')
                ->name('store');

            Route::patch('/{option}', [ProductOptionController::class, 'update'])
                ->middleware('password.confirm')
                ->name('update');

            Route::delete('/{option}', [ProductOptionController::class, 'destroy'])
                ->middleware('password.confirm')
                ->name('destroy');
        });
    });
});
