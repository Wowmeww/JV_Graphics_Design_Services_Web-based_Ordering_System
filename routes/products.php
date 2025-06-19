<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::middleware(['can:create,App\Models\Product', 'auth', 'verified'])->group(function () {
    Route::get("/products", [ProductController::class, "index"])->name("product.index");
    Route::get("/products/{product}", [ProductController::class, "show"])->name("product.show");
});
