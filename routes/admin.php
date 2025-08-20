<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DelegationController;
use App\Http\Controllers\Admin\DropdownController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EscortController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\OtherMemberController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\ArrivalController;
use Illuminate\Support\Facades\Route;

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
    Route::post('/set-current-event', [EventController::class, 'setCurrentEvent'])->name('events.setCurrentEvent');

    // Manage Other Interview Members
    Route::resource('other-interview-members', OtherMemberController::class);
    Route::get('/other-interview-members/{id}', [OtherMemberController::class, 'show'])->name('otherInterviewMembers.show');
    Route::get('/other-interview-members/edit/{id}', [OtherMemberController::class, 'edit'])->name('otherInterviewMembers.edit');

    // Manage Delegation Members

    Route::get('/delegations/search', [DelegationController::class, 'search'])->name('delegations.search');
    Route::get('/delegations/search-by-code', [DelegationController::class, 'searchByCode'])->name('delegations.searchByCode');
    Route::get('/delegations/members/{delegation}', [DelegationController::class, 'members'])->name('delegations.members');

    // Delegations
    Route::resource('delegations', DelegationController::class);
    Route::get('/delegations-get', [DelegationController::class, 'index']);
    Route::get('/delegations/edit/{id}', [DelegationController::class, 'edit'])->name('delegations.edit');
    Route::get('/delegations/delete/{id}', [DelegationController::class, 'edit'])->name('delegations.delete');

    // Delegate
    Route::get('/delegations/add-delegate/{id}', [DelegationController::class, 'addDelegate'])->name('delegations.addDelegate');
    Route::get('/delegations/edit-delegate/{delegation}/{delegate}', [DelegationController::class, 'editDelegate'])->name('delegations.editDelegate');
    Route::post('/delegations/{delegation}/delegates', [DelegationController::class, 'storeOrUpdateDelegate'])->name('delegations.storeDelegate');
    Route::put('/delegations/{delegation}/delegates/{delegate}', [DelegationController::class, 'storeOrUpdateDelegate'])->name('delegations.updateDelegate');
    Route::delete('/delegations/{delegation}/delegates/{delegate}', [DelegationController::class, 'destroyDelegate'])->name('delegations.destroyDelegate');

    // Travel
    Route::get('/delegations/add-travel/{id}', [DelegationController::class, 'addTravel'])->name('delegations.addTravel');
    Route::post('/delegations/submit-add-travel/{id}', [DelegationController::class, 'storeTravel'])->name('delegations.storeTravel');

    // Interview
    Route::get('/delegations/{delegation}/add-interview', [DelegationController::class, 'addInterview'])->name('delegations.addInterview');
    Route::get('/delegations/{delegation}/interviews/{interview}/edit', [DelegationController::class, 'editInterview'])
        ->name('delegations.editInterview')
        ->scopeBindings();
    Route::post('/delegations/{delegation}/interview/{interview?}', [DelegationController::class, 'storeOrUpdateInterview'])->name('delegations.storeOrUpdateInterview');
    Route::delete('/interviews/destroy/{interview}', [DelegationController::class, 'destroyInterview'])
        ->name('delegations.destroyInterview');

    // Attachments
    Route::post('/delegations/attachments-update/{id}', [DelegationController::class, 'updateAttachments'])->name('delegations.updateAttachment');
    Route::delete('/attachments/destroy/{id}', [DelegationController::class, 'destroyAttachment'])->name('attachments.destroy');

    // Arrivals and Departures
    Route::get('/arrivals', [DelegationController::class, 'arrivalsIndex'])->name('delegations.arrivalsIndex');
    Route::get('/departures', [DelegationController::class, 'departuresIndex'])->name('delegations.departuresIndex');
    Route::post('/travel-update/{transport}', [DelegationController::class, 'updateTravel'])->name('delegations.updateTravel');

    // Escorts
    Route::resource('escorts', EscortController::class);
    Route::get('/escorts/assign/{escort}', [EscortController::class, 'assignIndex'])->name('escorts.assignIndex');

    Route::post('escorts/{escort}/assign', [EscortController::class, 'assign'])->name('escorts.assign');
    Route::post('escorts/{escort}/unassign', [EscortController::class, 'unassign'])->name('escorts.unassign');
});

Route::get('/lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }
    return redirect()->back();
})->name('lang.switch');
