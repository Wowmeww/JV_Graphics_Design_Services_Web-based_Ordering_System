<?php

use App\Http\Controllers\DesignerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    Route::get('/designer/{product}', [DesignerController::class, 'index'])->name('designer');
});