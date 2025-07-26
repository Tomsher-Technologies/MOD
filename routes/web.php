<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Delegate\HomeController as DelegateHomeController;
use App\Http\Controllers\Escort\HomeController as EscortHomeController;
use App\Http\Controllers\Driver\HomeController as DriverHomeController;
use App\Http\Controllers\Hotel\HomeController as HotelHomeController;
use App\Http\Controllers\AuthController;

require __DIR__.'/admin.php';

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('web.login');
Route::get('logout', [AuthController::class, 'logout'])->name('web.logout');

// Delegate Module
Route::prefix('mod-delegate') ->middleware(['auth', 'check.permission']) ->group(function () {
    Route::get('/dashboard', [DelegateHomeController::class, 'dashboard'])->name('delegate.dashboard');
});

// Escort Module
Route::prefix('mod-escort') ->middleware(['auth', 'check.permission']) ->group(function () {
    Route::get('/dashboard', [EscortHomeController::class, 'dashboard'])->name('escort.dashboard');
});

// Driver Module
Route::prefix('mod-driver') ->middleware(['auth', 'check.permission']) ->group(function () {
    Route::get('/dashboard', [DriverHomeController::class, 'dashboard'])->name('driver.dashboard');
});

// Hotel 
Route::prefix('mod-hotel') ->middleware(['auth', 'check.permission']) ->group(function () {
    Route::get('/dashboard', [HotelHomeController::class, 'dashboard'])->name('hotel.dashboard');
});

