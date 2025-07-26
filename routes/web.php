<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Delegate\HomeController as DelegateHomeController;
use App\Http\Controllers\Escort\HomeController as EscortHomeController;
use App\Http\Controllers\Driver\HomeController as DriverHomeController;
use App\Http\Controllers\Hotel\HomeController as HotelHomeController;
use App\Http\Controllers\AuthController;

require __DIR__.'/admin.php';

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Delegate Module
Route::prefix('mod-delegate') ->middleware(['auth', 'check.permission']) ->group(function () {
    Route::get('/dashboard', [DelegateHomeController::class, 'index'])->name('delegate.dashboard');
});

// Escort Module
Route::prefix('mod-escort') ->middleware(['auth', 'check.permission']) ->group(function () {
    Route::get('/dashboard', [EscortHomeController::class, 'index'])->name('escort.dashboard');
});

// Driver Module
Route::prefix('mod-driver') ->middleware(['auth', 'check.permission']) ->group(function () {
    Route::get('/dashboard', [DriverHomeController::class, 'index'])->name('driver.dashboard');
});

// Hotel 
Route::prefix('mod-hotel') ->middleware(['auth', 'check.permission']) ->group(function () {
    Route::get('/dashboard', [HotelHomeController::class, 'index'])->name('hotel.dashboard');
});

