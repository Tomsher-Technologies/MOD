<?php

use App\Models\Country;
use App\Models\User;
use App\Models\Delegate;
use App\Models\Escort;
use App\Models\Driver;
use App\Models\Event;
use App\Models\Language;
use App\Models\Accommodation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        $root = '//' . $_SERVER['HTTP_HOST'];
        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        return $root;
    }
}


//highlights the selected navigation on admin panel
if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "!text-[#B68A35] rounded-lg !bg-[#F9F7ED]")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('areActiveWebRoutes')) {
    function areActiveWebRoutes(array $routes, $output = "side-active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}
function __db($key, $replace = [], $locale = null)
{
    $locale = $locale ?? app()->getLocale();

    // Try to fetch from cache or DB
    $translation = Cache::rememberForever("db_translation_{$locale}_{$key}", function () use ($locale, $key) {
        return \App\Models\Translation::where('label_key', $key)
            ->first()?->values->firstWhere('lang', $locale)?->value;
    });

    $translated = __($key, $replace, $locale);
    // Fallback to Laravel default if DB value is missing
    return $translation && trim($translation) !== ''
        ? strtr($translation, $replace)
        : ($translated !== $key && trim($translated) !== ''
            ? $translated
            : __($key, $replace, 'en'));
}

function getAllActiveLanguages()
{
    $languages = Language::where('status', 1)->orderBy('id')->get();
    return $languages;
}

function getActiveLanguage()
{
    if (Session::exists('locale')) {
        return Session::get('locale');
    }
    return 'en';
}

function uploadImage($type, $imageUrl, $filename = null)
{
    $data_url = '';
    $ext = $imageUrl->getClientOriginalExtension();

    $path = $type . '/';

    $filename = $path . $filename . '_' . time() . '_' . rand(10, 9999) . '.' . $ext;

    $imageContents = file_get_contents($imageUrl);

    // Save the original image in the storage folder
    Storage::disk('public')->put($filename, $imageContents);
    $data_url = Storage::url($filename);

    return $data_url;
}

function getUploadedImage(?string $path, string $default = 'assets/img/default_image.png'): string
{
    if ($path) {
        $relativePath = str_replace('/storage/', '', $path);
        if (Storage::disk('public')->exists($relativePath)) {
            return asset($path);
        }
    }

    return asset($default);
}

function formatFilePathsWithFullUrl(array $files): array
{
    return array_values(array_filter(array_map(function ($path) {
        // Strip starting slash to match disk paths
        $cleanPath = ltrim($path, '/');

        // Check existence in storage
        if (Storage::disk('public')->exists(str_replace('storage/', '', $cleanPath))) {
            return asset($path); // Or use asset($path)
        }

        return null;
    }, $files)));
}


function getUnreadNotifications()
{
    if (!Auth::check()) {
        return collect(); // return empty collection if not logged in
    }

    return Auth::user()->unreadNotifications;
}

function getUsersWithPermissions(array $permissions, string $guard = 'web')
{
    $users =  User::where(function ($query) use ($permissions, $guard) {
        $query->whereHas('permissions', function ($q) use ($permissions, $guard) {
            $q->whereIn('name', $permissions)->where('guard_name', $guard);
        })->orWhereHas('roles.permissions', function ($q) use ($permissions, $guard) {
            $q->whereIn('name', $permissions)->where('guard_name', $guard);
        });
    })->get();

    return $users;
}

function getUnreadNotificationCount()
{
    $user = Auth::guard('frontend')->user();

    $count = $user->unreadNotifications()->count();

    return $count;
}

function getAdminEventLogo()
{
    $eventId = session('current_event_id', getDefaultEventId() ?? null);
    $defaultEventLogo = Event::where('id', $eventId)->value('logo');

    if ($defaultEventLogo) {
        $relativePath = str_replace('/storage/', '', $defaultEventLogo);
        if (Storage::disk('public')->exists($relativePath)) {
            return asset($defaultEventLogo);
        }
    }

    return asset('assets/img/md-logo.svg');
}

function getAdminEventPDFLogo()
{
    $eventId = session('current_event_id', getDefaultEventId() ?? null);
    $defaultEventLogo = Event::where('id', $eventId)->value('logo');

    if ($defaultEventLogo) {
        $relativePath = str_replace('/storage/', '', $defaultEventLogo);
        if (Storage::disk('public')->exists($relativePath)) {
            return $defaultEventLogo;
        }
    }

    return 'assets/img/md-logo.svg';
}

function getCurrentEventName()
{
    $eventId = session('current_event_id', getDefaultEventId() ?? null);
    $event = Event::where('id', $eventId)->first();

    if ($event) {
        return $event->getTranslation('name');
    }
    return '';
}

function getModuleEventLogo()
{
    $defaultEventLogo = Event::where('is_default', 1)->value('logo');

    if ($defaultEventLogo) {
        $relativePath = str_replace('/storage/', '', $defaultEventLogo);
        if (Storage::disk('public')->exists($relativePath)) {
            return asset($defaultEventLogo);
        }
    }
    return asset('assets/img/md-logo.svg');
}


function getModuleAccountEventLogo()
{
    $id = session('current_event_id');

    $defaultEventLogo = Event::where('id', $id)->value('logo');

    if ($defaultEventLogo) {
        $relativePath = str_replace('/storage/', '', $defaultEventLogo);
        if (Storage::disk('public')->exists($relativePath)) {
            return asset($defaultEventLogo);
        }
    }
    return asset('assets/img/md-logo.svg');
}

function getAccommodationDetails($hotels)
{
    $details = Accommodation::with(['contacts'])->whereIn('id', $hotels)->get();
    return $details;
}

function getloginImage()
{
    $defaultEventImage = Event::where('is_default', 1)->value('image');

    if ($defaultEventImage) {
        $relativePath = str_replace('/storage/', '', $defaultEventImage);
        if (Storage::disk('public')->exists($relativePath)) {
            return asset($defaultEventImage);
        }
    }

    return asset('assets/img/login-img.jpg');
}

function generateEventCode()
{
    $lastEvent = Event::orderBy('created_at', 'desc')->first();

    if (!$lastEvent || !$lastEvent->code) {
        return 'EVT0001';
    }

    $lastNumber = (int) substr($lastEvent->code, 3);

    $newNumber = $lastNumber + 1;

    return 'EVT' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}

function getDefaultEventId()
{
    return \App\Models\Event::where('is_default', true)->value('id');
}

function getAllEvents()
{
    return \App\Models\Event::all();
}

function getDropDown($key)
{
    return \App\Models\Dropdown::where('code', $key)->with('options')->first() ?? [];
}

function storeUploadedFileToModuleFolder($file, $folder, $parentId, $subDir = 'files')
{
    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $extension = $file->getClientOriginalExtension();
    $date = date('YmdHis');
    $random = rand(1000, 9999);
    $filename = $originalName . '_' . $date . '_' . $random . '.' . $extension;
    $storageFolder = $folder . '/' . $parentId . '/' . $subDir;
    return $file->storeAs($storageFolder, $filename, 'public');
}


if (!function_exists('getRouteForPage')) {
    function getRouteForPage(string $pageKey, $params = null): ?string
    {
        $pageRoutes = [
            'delegations.index' => [
                'manage_delegations' => 'delegations.index',
                'del_manage_delegations' => 'delegations.index',
            ],
            'delegation.store' => [
                'add_delegations' => 'delegations.store',
                'del_add_delegations' => 'delegations.store',
            ],
            'delegation.show' => [
                'add_delegations' => 'delegations.show',
                'del_add_delegations' => 'delegations.show',
            ],
            'delegation.update' => [
                'add_delegations' => 'delegations.update',
                'del_add_delegations' => 'delegations.update',
            ],

            //Interview
            'delegation.storeInterview' => [
                'add_interviews' => 'delegations.storeOrUpdateInterview',
                'del_add_interviews' => 'delegations.storeOrUpdateInterview',
            ],
            'delegation.addInterview' => [
                'add_interviews' => 'delegations.addInterview',
                'del_add_interviews' => 'delegations.addInterview',
            ],
            'delegation.editInterview' => [
                'edit_interviews' => 'delegations.editInterview',
                'del_edit_interviews' => 'delegations.editInterview',
            ],
            'delegation.destroyInterview' => [
                'delete_interviews' => 'delegations.destroyInterview',
                'del_delete_interviews' => 'delegations.destroyInterview',
            ],
            'delegation.interviewsIndex' => [
                'view_interviews' => 'delegations.interviewsIndex',
                'del_view_interviews' => 'delegations.interviewsIndex',
            ],


            //Travel
            'delegation.addTravel' => [
                'add_travels' => 'delegations.addTravel',
                'add_travels' => 'delegations.addTravel',
            ],
            'delegation.storeTravel' => [
                'add_travels' => 'delegations.storeTravel',
                'del_add_travels' => 'delegations.storeTravel',
            ],
            'delegation.arrivalsIndex' => [
                'view_travels' => 'delegations.arrivalsIndex',
                'del_view_travels' => 'delegations.arrivalsIndex',
            ],


            //Delegate
            'delegation.storeDelegate' => [
                'add_delegate' => 'delegations.storeDelegate',
                'del_add_delegate' => 'delegations.storeDelegate',
            ],
            'delegation.editDelegate' => [
                'edit_delegate' => 'delegations.editDelegate',
                'del_edit_delegate' => 'delegations.editDelegate',
            ],
            'delegation.destroyDelegate' => [
                'delete_delegate' => 'delegations.destroyDelegate',
                'del_delete_delegate' => 'delegations.destroyDelegate',
            ],
            'delegation.edit' => [
                'edit_delegations' => 'delegations.edit',
                'del_edit_delegations' => 'delegations.edit',
            ],
            'delegation.addDelegate' => [
                'add_delegate' => 'delegations.addDelegate',
                'del_add_delegate' => 'delegations.addDelegate',
            ],


            'delegation.searchByCode' => [
                'manage_delegations' => 'delegations.searchByCode',
                'del_manage_delegations' => 'delegations.searchByCode',
            ],
            'delegation.search' => [
                'manage_delegations' => 'delegations.search',
                'del_manage_delegations' => 'delegations.search',
            ],

            // Escorts
            'escorts.index' => [
                'manage_escorts' => 'escorts.index',
                'del_manage_escort' => 'escorts.index',
            ],
            'escorts.show' => [
                'manage_escorts' => 'escorts.show',
                'del_manage_escort' => 'escorts.show',
            ],
            'escorts.create' => [
                'add_escorts' => 'escorts.create',
                'del_add_escorts' => 'escorts.create',
            ],
            'escorts.store' => [
                'add_escorts' => 'escorts.store',
                'del_add_escorts' => 'escorts.store',
            ],
            'escorts.edit' => [
                'edit_escorts' => 'escorts.edit',
                'del_edit_escorts' => 'escorts.edit',
            ],
            'escorts.update' => [
                'edit_escorts' => 'escorts.update',
                'del_edit_escorts' => 'escorts.update',
            ],
            'escorts.unassign' => [
                'edit_escorts' => 'escorts.unassign', // Assuming permission to edit escorts allows unassigning
                'del_edit_escorts' => 'escorts.unassign',
            ],
            'escorts.assignIndex' => [
                'edit_escorts' => 'escorts.assignIndex', // Assuming permission to edit escorts allows assigning
                'del_edit_escorts' => 'escorts.assignIndex',
            ],
            'escorts.status' => [
                'edit_escorts' => 'escorts.status', // Assuming permission to edit escorts allows changing status
                'del_edit_escorts' => 'escorts.status',
            ],

            // Drivers
            'drivers.index' => [
                'manage_drivers' => 'drivers.index',
                'del_manage_drivers' => 'drivers.index',
            ],
            'drivers.show' => [
                'manage_drivers' => 'drivers.show',
                'del_manage_drivers' => 'drivers.show',
            ],
            'drivers.create' => [
                'add_drivers' => 'drivers.create',
                'del_add_drivers' => 'drivers.create',
            ],
            'drivers.edit' => [
                'edit_drivers' => 'drivers.edit',
                'del_edit_drivers' => 'drivers.edit',
            ],
            'drivers.update' => [
                'edit_drivers' => 'drivers.update',
                'del_edit_drivers' => 'drivers.update',
            ],
            'drivers.assign' => [
                'edit_drivers' => 'drivers.assign', // Assuming permission to edit drivers allows assigning
                'del_edit_drivers' => 'drivers.assign',
            ],
            'drivers.unassign' => [
                'edit_drivers' => 'drivers.unassign', // Assuming permission to edit drivers allows unassigning
                'del_edit_drivers' => 'drivers.unassign',
            ],
            'drivers.assignIndex' => [
                'edit_drivers' => 'drivers.assignIndex', // Assuming permission to edit drivers allows assigning
                'del_edit_drivers' => 'drivers.assignIndex',
            ],
            'drivers.status' => [
                'edit_drivers' => 'drivers.status', // Assuming permission to edit drivers allows changing status
                'del_edit_drivers' => 'drivers.status',
            ],
            'drivers.store' => [
                'edit_drivers' => 'drivers.store', // Assuming permission to edit drivers allows changing status
                'del_edit_drivers' => 'drivers.store',
            ],


            //Members
            'delegation.members' => [
                'add_interviews' => '/mod-admin/delegations/members',
                'del_add_interviews' => '/mod-admin/delegations/members',
            ],

            //Attachments
            'attachments.destroy' => [
                'manage_delegations' => 'attachments.destroy',
                'del_manage_delegations' => 'attachments.destroy',
            ],
            'delegations.updateAttachment' => [
                'manage_delegations' => 'delegations.updateAttachment',
                'del_manage_delegations' => 'delegations.updateAttachment',
            ],

            //Other Interview Members
            'other-interview-members.index' => [
                'manage_other_interview_members' => 'other-interview-members.index',
                'del_manage_other_interview_members' => 'other-interview-members.index',
            ],
            'otherInterviewMembers.edit' => [
                'edit_other_interview_members' => 'otherInterviewMembers.edit',
                'del_edit_other_interview_members' => 'otherInterviewMembers.edit',
            ],
            'otherInterviewMembers.show' => [
                'view_other_interview_members' => 'otherInterviewMembers.show',
                'del_view_other_interview_members' => 'otherInterviewMembers.show',
            ],


            // Accommodation
            'accommodations.index' => [
                'manage_accommodations' => 'accommodations.index',
                'hotel_manage_accommodations' => 'accommodations.index',
            ],
            'accommodations.show' => [
                'manage_accommodations' => 'accommodations.show',
                'hotel_manage_accommodations' => 'accommodations.show',
            ],
            'accommodations.create' => [
                'add_accommodations' => 'accommodations.create',
                'hotel_add_accommodations' => 'accommodations.create',
            ],
            'accommodations.store' => [
                'add_accommodations' => 'accommodations.store',
                'hotel_add_accommodations' => 'accommodations.store',
            ],
            'accommodations.edit' => [
                'edit_accommodations' => 'accommodations.edit',
                'hotel_edit_accommodations' => 'accommodations.edit',
            ],
            'accommodations.update' => [
                'edit_accommodations' => 'accommodations.update',
                'hotel_edit_accommodations' => 'accommodations.update',
            ],
            'accommodations.import.form' => [
                'import_accommodations' => 'accommodations.import.form',
                'hotel_import_accommodations' => 'accommodations.import.form',
            ],
            'accommodations.import' => [
                'import_accommodations' => 'accommodations.import',
                'hotel_import_accommodations' => 'accommodations.import',
            ],
            'accommodations.assign' => [
                'assign_accommodations' => 'accommodations.edit',
                'hotel_assign_accommodations' => 'accommodations.edit',
            ],
            'accommodation-rooms.destroy' => [
                'edit_accommodations' => 'accommodation-rooms.destroy',
                'hotel_edit_accommodations' => 'accommodation-rooms.destroy',
            ],
            'accommodation-delegations' => [
                'view_accommodation_delegations' => 'accommodation-delegations',
                'hotel_view_accommodation_delegations' => 'accommodation-delegations',
            ],
            'accommodation-delegation-view' => [
                'view_accommodation_delegations' => 'accommodation-delegation-view',
                'hotel_view_accommodation_delegations' => 'accommodation-delegation-view',
            ],
        ];

        $user = auth()->user();
        if (!$user) {
            return null;
        }

        if (!isset($pageRoutes[$pageKey])) {
            return null;
        }

        foreach ($pageRoutes[$pageKey] as $permission => $routeNameOrUrl) {
            if ($user->can($permission)) {
                if (is_string($routeNameOrUrl) && str_starts_with($routeNameOrUrl, '/')) {
                    return $routeNameOrUrl;
                }

                if ($params === null) {
                    return route($routeNameOrUrl);
                }

                if (is_array($params)) {
                    return route($routeNameOrUrl, $params);
                }

                return route($routeNameOrUrl, $params);
            }
        }

        return "#";
    }
}

function getAllCountries()
{
    return Country::where('status', 1)->orderBy('sort_order', 'asc')->get();
}


if (! function_exists('getAllDrivers')) {
    function getAllDrivers()
    {
        $currentEventId = session('current_event_id', getDefaultEventId());
        return Driver::where('event_id', $currentEventId)->where('status', 1)->orderBy('code')
            ->get();
    }
}

if (! function_exists('getAllEscorts')) {
    function getAllEscorts()
    {
        $currentEventId = session('current_event_id', getDefaultEventId());
        return Escort::where('event_id', $currentEventId)->where('status', 1)->orderBy('code')
            ->get();
    }
}


if (! function_exists('getCountriesByContinent')) {
    function getCountriesByContinent($continentId)
    {
        return Country::where('continent_id', $continentId)
                        ->where('status', 1)
                        ->orderBy('sort_order', 'asc')
                        ->get();
    }
}

if (!function_exists('can')) {
    function can(string|array $permissions): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        $permissions = is_array($permissions) ? $permissions : [$permissions];

        foreach ($permissions as $permission) {
            // if ($user->can($permission)) {
            //     return true;
            // }
             if ($user->getDirectPermissions()->pluck('name')->contains($permission)) {
                return true;
            }
        }

        return false;
    }
}

function isEscort()
{
    $user = auth()->user();
    return $user && $user->user_type === 'escort';
}

function isDriver()
{
    $user = auth()->user();
    return $user && $user->user_type === 'driver';
}

function isHotel()
{
    $user = auth()->user();
    return $user && $user->user_type === 'hotel';
}

function getRoomAssignmentStatus($delegationId)
{
    $delegates = Delegate::where('delegation_id', $delegationId)->where('accommodation', 1)
        ->pluck('current_room_assignment_id');

    $escorts = Escort::whereIn('id', function ($q) use ($delegationId) {
        $q->select('escort_id')
            ->from('delegation_escorts')
            ->where('status', 1)
            ->where('delegation_id', $delegationId);
    })
        ->pluck('current_room_assignment_id');

    $drivers = Driver::whereIn('id', function ($q) use ($delegationId) {
        $q->select('driver_id')
            ->from('delegation_drivers')
            ->where('status', 1)
            ->where('delegation_id', $delegationId);
    })
        ->pluck('current_room_assignment_id');

    $all = $delegates->merge($escorts)->merge($drivers);

    if ($all->count() === 0) {
        return 0;
    }

    $assignedCount = $all->filter(fn($id) => !is_null($id))->count();
    $totalCount    = $all->count();

    if ($assignedCount === 0) {
        return 0;
    } elseif ($assignedCount === $totalCount) {
        return 1;
    } else {
        return 2;
    }
}

function shadeColor($hex, $percent)
{
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $r = max(0, min(255, $r + ($percent / 100 * 255)));
    $g = max(0, min(255, $g + ($percent / 100 * 255)));
    $b = max(0, min(255, $b + ($percent / 100 * 255)));

    return sprintf("#%02x%02x%02x", $r, $g, $b);
}


function humanize(string|null $text): string
{
    if (!$text) return 'N/A';

    $text = str_replace('_', ' ', strtolower($text));

    return ucwords($text);
}
