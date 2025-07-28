<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\DropdownController;
use App\Http\Controllers\Admin\EventController;

Route::prefix('mod-admin')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('post.login');
    Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
});

Route::prefix('mod-admin')->middleware(['web', 'auth', 'user_type:admin,staff'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Manage staffs
    Route::resource('staffs', StaffController::class);
    Route::get('/staffs/edit/{id}', [StaffController::class, 'edit'])->name('staffs.edit');
    Route::get('/staffs/destroy/{id}', [StaffController::class, 'destroy'])->name('staffs.destroy');
    Route::post('/staff/status', [StaffController::class, 'updateStatus'])->name('staff.status');
    Route::get('/get-roles-by-module/{module}', [StaffController::class, 'getByModule']);

    
    // Manage roles & permissions
    Route::resource('roles', RoleController::class);
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::get('/get-permissions-by-module/{module}', [RoleController::class, 'getPermissionsByModule'])->name('roles.edit-permissions-by-module');

    // Manage label translations
    Route::get('/translations', [TranslationController::class, 'index'])->name('translations.index');
    Route::post('/translations', [TranslationController::class, 'store'])->name('translations.store');
    Route::post('/translations/{id}', [TranslationController::class, 'update'])->name('translations.update');

    // Manage dynamic dropdowns
    Route::get('/dropdowns', [DropdownController::class, 'index'])->name('dropdowns.index');
    Route::get('/dropdowns/{dropdown}/options', [DropdownController::class, 'showOptions'])->name('dropdowns.options.show');
    Route::post('/dropdowns/options', [DropdownController::class, 'storeOption'])->name('dropdowns.options.store');
    Route::put('/dropdowns/options/{option}', [DropdownController::class, 'updateOption'])->name('dropdowns.options.update');
    Route::post('/dropdowns/options/status', [DropdownController::class, 'updateStatus'])->name('dropdowns.options.status');
    Route::get('/dropdowns/bulk-import', [DropdownController::class, 'bulkImport'])->name('dropdowns.bulk.import');
    Route::post('/dropdowns/options/import', [DropdownController::class, 'import'])->name('admin.dropdowns.import');

    // Manage Events
    Route::resource('events', EventController::class);
    Route::get('/events/edit/{id}', [EventController::class, 'edit'])->name('events.edit');
    Route::post('events/{event}/set-default', [EventController::class, 'setDefault'])->name('events.setDefault');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/assign-user', [EventController::class, 'assignUsers'])->name('events.assignUsers');
    Route::post('/events/{event}/unassign-user/{assigned}', [EventController::class, 'unassignUser'])->name('events.unassignUser');


});

Route::get('/lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }
    return redirect()->back();
})->name('lang.switch');
