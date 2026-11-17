<?php

use App\Http\Controllers\ColdTripController;
use App\Http\Controllers\CargoTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\TruckController;
use Illuminate\Support\Facades\Route;

// Homepage: redirect to dashboard
Route::get('/', function () {
    return redirect('/dashboard');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('drivers', DriverController::class)->except('show');
    Route::resource('cargo-types', CargoTypeController::class)->except('show');
    Route::resource('hubs', HubController::class)->except('show');
    Route::resource('trucks', TruckController::class);
    Route::resource('cold-trips', ColdTripController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
