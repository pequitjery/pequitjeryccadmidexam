<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\ColdTripController;

// Homepage: redirect to dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Truck Management CRUD
Route::resource('trucks', TruckController::class);

// Cold Trip Management CRUD
Route::resource('cold-trips', ColdTripController::class);
