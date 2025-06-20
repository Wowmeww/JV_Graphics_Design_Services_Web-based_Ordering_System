<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::middleware(['can:create,App\Models\Product', 'auth', 'verified'])->group(function () {
    Route::get("/products", [ProductController::class, "index"])->name("product.index");
    Route::get("/products/create", [ProductController::class, "create"])->name("product.create");
    Route::post("/products", [ProductController::class, "store"])->name("product.store");
    Route::get("/products/{product}/{option?}", [ProductController::class, "show"])->name("product.show");
    Route::get("/products/{product}/{option?}/edit", [ProductController::class, "edit"])->name("product.edit");
    Route::patch("/products/{product}/{option?}", [ProductController::class, "update"])->name("product.update");
    Route::delete("/products/{product}/{option?}", [ProductController::class, "destroy"])->name("product.destroy");
});
