<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Delegate\HomeController as DelegateHomeController;
use App\Http\Controllers\Escort\HomeController as EscortHomeController;
use App\Http\Controllers\Driver\HomeController as DriverHomeController;
use App\Http\Controllers\Hotel\HomeController as HotelHomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontController;

require __DIR__.'/admin.php';

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/about-us', [FrontController::class, 'aboutUs'])->name('about-us');
Route::get('/committees', [FrontController::class, 'committees'])->name('committees');
Route::get('/news', [FrontController::class, 'news'])->name('news');
Route::get('/news-details/{id}', [FrontController::class, 'newsDetails'])->name('news-details');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('web.login');
Route::get('logout', [AuthController::class, 'logout'])->name('web.logout');
Route::get('/force-password', [AuthController::class, 'showForcePasswordForm'])->name('force.password.form');
Route::post('/force-password', [AuthController::class, 'updateForcePassword'])->name('force.password.update');
    
Route::get('/lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }
    return redirect()->back();
})->name('lang.switch');

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

