<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DelegationController;
use App\Http\Controllers\Admin\DropdownController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EscortController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\OtherMemberController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\ArrivalController;
use App\Http\Controllers\Admin\AccommodationController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\AlertController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\CommitteeController;
use App\Http\Controllers\Admin\EventPageController;
use App\Http\Controllers\Admin\FloorPlanController;
use App\Http\Controllers\Admin\ImportLogController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\LogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::prefix('mod-admin')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/check-username', [LoginController::class, 'checkUsername'])->name('check.username');
    Route::post('login', [LoginController::class, 'login'])->name('post.login');
    Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
});

Route::prefix('mod-events')->middleware(['web', 'auth'])->group(function () {
    Route::get('/clear-cache', function () {
        Artisan::call('optimize:clear');
        return back()->with('success', 'All cache cleared successfully!');
    })->name('clear.cache');

    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/tables/{table}', [AdminDashboardController::class, 'dashboardTables'])->name('admin.dashboard.tables');

    // Manage countries
    Route::resource('countries', CountryController::class);
    Route::post('/countries/status', [CountryController::class, 'updateStatus'])->name('countries.status');
    Route::get('/get-countries', [CountryController::class, 'getByContinents'])->name('countries.by-continent');

    // Manage staffs
    Route::resource('staffs', StaffController::class);
    Route::get('/staffs/edit/{id}', [StaffController::class, 'edit'])->name('staffs.edit');
    Route::get('/staffs/destroy/{id}', [StaffController::class, 'destroy'])->name('staffs.destroy');
    Route::post('/staff/status', [StaffController::class, 'updateStatus'])->name('staff.status');
    Route::get('/get-roles-by-module/{module}', [StaffController::class, 'getByModule']);
    Route::get('/staff/import', [StaffController::class, 'showForm'])->name('users.import.form');
    Route::post('/staff/import', [StaffController::class, 'import'])->name('users.import');


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
    Route::get('/dropdowns/export', [DropdownController::class, 'export'])->name('dropdowns.export');

    // Manage Events
    Route::resource('events', EventController::class);
    Route::get('/events/edit/{id}', [EventController::class, 'edit'])->name('events.edit');
    Route::post('events/{event}/set-default', [EventController::class, 'setDefault'])->name('events.setDefault');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/assign-user', [EventController::class, 'assignUsers'])->name('events.assignUsers');
    Route::post('/events/{event}/unassign-user/{assigned}', [EventController::class, 'unassignUser'])->name('events.unassignUser');
    Route::post('/set-current-event', [EventController::class, 'setCurrentEvent'])->name('events.setCurrentEvent');
    Route::post('/event/user/status', [EventController::class, 'updateEventUserStatus'])->name('event.user.status');

    // Manage Other Interview Members
    Route::resource('other-interview-members', OtherMemberController::class);
    Route::get('/other-interview-members/{id}', [OtherMemberController::class, 'show'])->name('otherInterviewMembers.show');
    Route::get('/other-interview-members/edit/{id}', [OtherMemberController::class, 'edit'])->name('otherInterviewMembers.edit');

    // Manage Delegation Members

    Route::get('/delegations/search', [DelegationController::class, 'search'])->name('delegations.search');
    Route::get('/delegations/search-by-code', [DelegationController::class, 'searchByCode'])->name('delegations.searchByCode');
    Route::get('/delegations/members/{delegation}', [DelegationController::class, 'members'])->name('delegations.members');

    // Delegations

    // Delegation Import
    Route::get('/delegations/import', [DelegationController::class, 'showImportForm'])->name('delegations.import.form');
    Route::post('/delegations/import/delegations', [DelegationController::class, 'importDelegations'])->name('delegations.import.delegations');
    Route::post('/delegations/import/delegates', [DelegationController::class, 'importDelegatesWithTravels'])->name('delegations.import.delegates');
    Route::post('/delegations/import/attachments', [DelegationController::class, 'importAttachments'])->name('delegations.import.attachments');

    Route::resource('delegations', DelegationController::class);
    Route::get('/delegations-get', [DelegationController::class, 'index']);
    Route::get('/delegations/edit/{id}', [DelegationController::class, 'edit'])->name('delegations.edit');
    Route::get('/delegations/delete/{id}', [DelegationController::class, 'edit'])->name('delegations.delete');
    Route::delete('/delegations/{delegation}', [DelegationController::class, 'destroy'])->name('delegations.destroy');


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
    Route::get('/escorts/import', [EscortController::class, 'showImportForm'])->name('escorts.import.form');
    Route::post('/escorts/import', [EscortController::class, 'import'])->name('escorts.import');
    Route::get('/escorts/search', [EscortController::class, 'search'])->name('escorts.search');

    Route::resource('escorts', EscortController::class);
    Route::post('/escorts/status', [EscortController::class, 'updateStatus'])->name('escorts.status');
    Route::get('/escorts/assign/{escort}', [EscortController::class, 'assignIndex'])->name('escorts.assignIndex');


    Route::post('escorts/{escort}/assign', [EscortController::class, 'assign'])->name('escorts.assign');
    Route::post('escorts/{escort}/unassign', [EscortController::class, 'unassign'])->name('escorts.unassign');

    // Drivers

    Route::get('/drivers/import', [DriverController::class, 'showImportForm'])->name('drivers.import.form');
    Route::post('/drivers/import', [DriverController::class, 'import'])->name('drivers.import');
    Route::get('/drivers/search', [DriverController::class, 'search'])->name('drivers.search');

    Route::resource('drivers', DriverController::class);
    Route::post('/drivers/status', [DriverController::class, 'updateStatus'])->name('drivers.status');
    Route::get('/drivers/assign/{driver}', [DriverController::class, 'assignIndex'])->name('drivers.assignIndex');

    Route::post('drivers/{driver}/assign', [DriverController::class, 'assign'])->name('drivers.assign');
    Route::post('drivers/{driver}/unassign', [DriverController::class, 'unassign'])->name('drivers.unassign');

    // Interviews
    Route::get('/interviews', [DelegationController::class, 'interviewsIndex'])->name('delegations.interviewsIndex');

    // Manage Accommodations
    Route::resource('accommodations', AccommodationController::class);
    Route::get('/accommodations/edit/{id}', [AccommodationController::class, 'edit'])->name('accommodations.edit');
    Route::post('/accommodation-rooms/destroy/{id}', [AccommodationController::class, 'destroyRooms'])->name('accommodation-rooms.destroy');
    Route::get('/accommodation/import', [AccommodationController::class, 'showImportForm'])->name('accommodations.import.form');
    Route::post('/accommodation/import', [AccommodationController::class, 'import'])->name('accommodations.import');
    Route::get('/export-room-types', [AccommodationController::class, 'exportRoomTypes'])->name('export.room.types');
    Route::get('/accommodation-delegations', [AccommodationController::class, 'accommodationDelegations'])->name('accommodation-delegations');
    Route::get('/accommodation-delegation-view/{id}', [AccommodationController::class, 'accommodationDelegationView'])->name('accommodation-delegation-view');
    Route::get('/accommodation/{id}/rooms', [AccommodationController::class, 'getHotelRooms'])->name('accommodation.rooms');
    Route::post('/accommodation/room-assign', [AccommodationController::class, 'assignRoom'])->name('accommodation.assign-rooms');
    Route::get('/accommodation/hotel/{hotel}/occupancy', [AccommodationController::class, 'hotelOccupancy'])->name('accommodation.occupancy');
    Route::post('/accommodation/room-unassign', [AccommodationController::class, 'unassignAccommodation'])->name('accommodation.remove-rooms');

    Route::get('/add-external-accommodation/{id}', [AccommodationController::class, 'addExternalMembers'])->name('external_accommodations.add');
    Route::post('/add-external-accommodation', [AccommodationController::class, 'storeExternalMembers'])->name('admin.external-members.store');
    Route::get('/external-accommodations', [AccommodationController::class, 'getExternalMembers'])->name('admin.view-external-members');
    Route::get('/external-accommodations/{id}/edit', [AccommodationController::class, 'editExternalMembers'])->name('external-members.edit');
    Route::put('/external-accommodations/{id}', [AccommodationController::class, 'updateExternalMembers'])->name('admin.external-members.update');
    Route::delete('/external-accommodations/{id}', [AccommodationController::class, 'destroyExternalMembers'])->name('admin.external-members.destroy');

    // Floor Plans
    Route::resource('floor-plans', FloorPlanController::class);
    Route::delete('/floor-plans/{floorPlan}/file/{fileIndex}', [FloorPlanController::class, 'queueFileDeletion'])->name('floor-plans.delete-file');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/redirect', [NotificationController::class, 'redirectToModule'])->name('notifications.redirect');

    // Alerts
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
    Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');
    Route::get('/alerts/{alert}', [AlertController::class, 'show'])->name('alerts.show');
    Route::get('/alerts/latest', [AlertController::class, 'getLatestAlert'])->name('alerts.latest');
    Route::post('/alerts/{id}/mark-as-read', [AlertController::class, 'markAsRead'])->name('alerts.markAsRead');

    // Badge Printed Delegates
    Route::get('/badge-printed-delegates', [DelegationController::class, 'badgePrintedIndex'])->name('delegates.badgePrintedIndex');
    Route::post('/badge-printed-status', [DelegationController::class, 'updateBadgePrintedStatus'])->name('delegates.updateBadgePrintedStatus');
    Route::get('/export-non-badge-printed', [DelegationController::class, 'exportNonBadgePrinted'])->name('delegates.exportNonBadgePrinted');
    Route::get('/export-badge-printed-delegates', [DelegationController::class, 'exportBadgePrintedDelegates'])->name('delegates.exportBadgePrintedDelegates');
    Route::get('/export-non-badge-printed-delegates', [DelegationController::class, 'exportNonBadgePrintedDelegates'])->name('delegates.exportNonBadgePrintedDelegates');
    Route::get('/export-delegations', [DelegationController::class, 'exportDelegations'])->name('delegates.exportDelegations');
    Route::get('/export-delegates', [DelegationController::class, 'exportDelegates'])->name('delegates.exportDelegates');

    //Manage news
    Route::resource('news', NewsController::class);
    Route::post('/news/status', [NewsController::class, 'updateStatus'])->name('news.status');

    //Manage Committee
    Route::resource('committees', CommitteeController::class);
    Route::post('/committees/status', [CommitteeController::class, 'updateStatus'])->name('committees.status');

    //Manage Page Contents
    Route::get('event-pages', [EventPageController::class, 'index'])->name('event_pages.index');
    Route::get('event-pages/edit/{id}', [EventPageController::class, 'edit'])->name('event_pages.edit');
    Route::post('event-pages/{id}/update', [EventPageController::class, 'update'])->name('event_pages.update');

    // Account
    Route::get('/profile', [AdminDashboardController::class, 'account'])->name('account');
    Route::post('/profile/change-password', [AdminDashboardController::class, 'changePassword'])->name('staffs.change-password');

    // Report Section
    Route::get('/reports/delegations', [ReportController::class, 'reportsDelegations'])->name('reports-delegations');
    Route::get('/report/delegations/{id}', [ReportController::class, 'showReportsDelegations'])->name('reports-delegations.show');
    Route::get('/delegations/export-pdf/{id}', [ReportController::class, 'exportReportDelegationPdf'])->name('delegations.exportPdf');

    Route::get('/logs', [LogController::class, 'showLog'])->name('admin.logs');

    // Import Logs
    Route::get('/import-logs', [ImportLogController::class, 'index'])->name('admin.import-logs.index');
    Route::post('/import-logs/clear', [ImportLogController::class, 'clearLogs'])->name('admin.import-logs.clear');
});

Route::get('/lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }
    return redirect()->back();
})->name('lang.switch');
