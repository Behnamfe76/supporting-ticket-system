<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\TemporaryUploadController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'role:user'])->name('dashboard');

Route::group(['middleware' => ['auth', 'verified']], function () {
    // tickets
    Route::group(['middleware' => ['role:user']], function () {
        Route::resource("tickets", TicketController::class)
            ->middleware('role:user')
            ->scoped(['ticket' => "slug"]);

        Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    });

    // Temporary upload route
    Route::post('/temporary-upload', [TemporaryUploadController::class, 'store'])->name('temporary-upload');
});

Route::middleware(['auth', 'verified', 'role:super-admin|admin|staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::resource("tickets", AdminTicketController::class)->scoped(['ticket' => "slug"])->only(['index', 'show']);
        Route::post('/tickets/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
    });

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
