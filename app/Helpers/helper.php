<?php

use App\Models\User;
use App\Models\Event;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
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
    function areActiveRoutes(array $routes, $output = "!text-[#B68A35] py-2 px-4 rounded-lg !bg-[#F9F7ED]")
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
    $defaultEventLogo = Event::where('is_default', 1)->value('logo');

    if ($defaultEventLogo) {
        $relativePath = str_replace('/storage/', '', $defaultEventLogo);
        if (Storage::disk('public')->exists($relativePath)) {
            return asset($defaultEventLogo);
        }
    }

    return asset('assets/img/md-logo.svg');
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
                'manage_interviews' => 'delegations.editInterview',
                'del_manage_interviews' => 'delegations.editInterview',
            ],
            'delegation.destroyInterview' => [
                'delete_interviews' => 'delegations.destroyInterview',
                'del_delete_interviews' => 'delegations.destroyInterview',
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
                'del_manage_escorts' => 'escorts.index',
            ],
            'escorts.show' => [
                'manage_escorts' => 'escorts.show',
                'del_manage_escorts' => 'escorts.show',
            ],
            'escorts.create' => [
                'add_escorts' => 'escorts.create',
                'del_add_escorts' => 'escorts.create',
            ],
            'escorts.edit' => [
                'edit_escorts' => 'escorts.edit',
                'del_edit_escorts' => 'escorts.edit',
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
            'attachments.edit' => [
                'manage_delegations' => 'delegations.updateAttachment',
                'del_manage_delegations' => 'delegations.updateAttachment',
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
