<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;


Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products/fetch', [ShopController::class, 'fetch'])->name('shop.fetch');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
