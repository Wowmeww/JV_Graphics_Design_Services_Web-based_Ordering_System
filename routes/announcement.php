<?php

use App\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth',  'can:accessAsAdmin'])->group(function () {
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcement.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcement.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcement.edit');
    Route::patch('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcement.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcement.destroy');
});
