<?php

use App\Http\Controllers\ColdTripController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TruckController;
use Illuminate\Support\Facades\Route;

// Homepage: redirect to dashboard
Route::get('/', function () {
    return redirect('/dashboard');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('trucks', TruckController::class);
    Route::resource('cold-trips', ColdTripController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
