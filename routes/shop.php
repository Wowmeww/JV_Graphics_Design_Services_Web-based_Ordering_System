<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;


Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products/fetch', [ShopController::class, 'fetch'])->name('shop.fetch');
Route::get('/shop/{product}/{option?}', [ShopController::class, 'show'])->name('shop.show');

Route::middleware(['auth'])->group(function () {
    Route::post('/shop/cart/{product}/{option?}', [ShopController::class, 'addToCart'])->name('shop.cart');
    Route::delete('/shop/cart/{product}/{option?}', [ShopController::class, 'deleteCartItem']);
    Route::post('/shop/wishlist/{product}/{option?}', [ShopController::class, 'addToWishlist'])->name('shop.wishlist');
    Route::post('/shop/order/{product}/{option?}', [ShopController::class, 'addToOrder'])->name('shop.order');
});
