<?php

use App\Http\Controllers\TemporaryUploadController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth', 'verified']], function () {
    // tickets
    Route::resource("tickets", TicketController::class)->scoped(['ticket' => "slug"]);

    // Temporary upload route
    Route::post('/temporary-upload', [TemporaryUploadController::class, 'store'])->name('temporary-upload')  ;
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
