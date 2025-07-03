<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RatingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products/fetch', [ShopController::class, 'fetch'])->name('shop.fetch');
Route::get('/shop/{product}/{option?}', [ShopController::class, 'show'])->name('shop.show');

Route::middleware(['auth'])->group(function () {
    // CART ROUTES
    Route::post('/shop-cart/{product}/{option?}', [CartController::class, 'store'])->name('cart.store');
    Route::get('/shop-cart/item/{cartItem}/edit', [CartController::class, 'edit'])->name('cart.edit');
    Route::patch('/shop-cart/item/{cartItem}/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/shop-cart/item/{cartItem?}', [CartController::class, 'destroy'])->name('cart.destroy');
    // WISHLIST ROUTES
    Route::post('/shop-wishlist/{product}/{option?}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::get('/shop-wishlist/item/{wishlistItem}/edit', [WishlistController::class, 'edit'])->name('wishlist.edit');
    Route::patch('/shop-wishlist/item/{wishlistItem}/update', [WishlistController::class, 'update'])->name('wishlist.update');
    Route::delete('/shop-wishlist/item/{wishlistItem?}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // ORDER ROUTES
    Route::get('/shop-orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/shop-orders/create', [OrderController::class, 'create'])->name('order.create');
    Route::get('/shop-orders/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/shop-orders', [OrderController::class, 'store'])->name('order.store');
    Route::get('/shop-orders/{order}/edit', [OrderController::class, 'edit'])->name('order.edit');
    Route::patch('/shop-orders/{order}', [OrderController::class, 'update'])->name('order.update');
    Route::delete('/shop-orders/{order}', [OrderController::class, 'destroy'])->name('order.destroy');

    // RATING
    Route::post('/rating/{order}', [RatingController::class, 'store'])->name('rating.store');
});
