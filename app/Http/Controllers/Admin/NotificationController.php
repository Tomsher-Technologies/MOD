<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = auth()->user()->notifications()->whereNull('alert_id')
            ->latest()->paginate(10);

        $allShownIds = collect($notifications->items())->pluck('id');

        auth()->user()->unreadNotifications()
            ->whereIn('id', $allShownIds)
            ->update(['read_at' => now()]);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function redirectToModule($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found.');
        }

        $notification->update(['read_at' => now()]);

        $data = $notification->data;
        $module = $data['module'] ?? null;
        $action = $data['action'] ?? null;
        $delegationId = $data['delegation_id'] ?? null;
        $submoduleId = $data['submodule_id'] ?? null;
        $moduleType = $data['module_type'] ?? null;

        if ($delegationId ) {
            try {
                $delegation = \App\Models\Delegation::find($delegationId);
                if ($delegation) {
                    return redirect()->route('delegations.show', $delegationId);
                }
            } catch (\Exception $e) {
                return redirect()->route('delegations.index');
            }
        } elseif ($module && $submoduleId) {
            switch (strtolower($module)) {
                case 'escorts':
                    try {
                        $escort = \App\Models\Escort::find($submoduleId);
                        if ($escort) {
                            return redirect()->route('escorts.edit', $submoduleId);
                        }
                    } catch (\Exception $e) {
                        return redirect()->route('escorts.index');
                    }
                    break;
                case 'drivers':
                    try {
                        $driver = \App\Models\Driver::find($submoduleId);
                        if ($driver) {
                            return redirect()->route('drivers.edit', $submoduleId);
                        }
                    } catch (\Exception $e) {
                        return redirect()->route('drivers.index');
                    }
                    break;
                default:
                    return redirect()->route('notifications.index');
            }
        } elseif ($module) {
            switch (strtolower($module)) {
                case 'escorts':
                    $escortName = null;
                    if (isset($data['changes']['escort_name'])) {
                        $escortName = $data['changes']['escort_name'];
                    } elseif (isset($data['changes']['member_name'])) {
                        $escortName = $data['changes']['member_name'];
                    }
                    
                    if ($escortName) {
                        return redirect()->route('escorts.index', ['search' => $escortName]);
                    } else {
                        return redirect()->route('escorts.index');
                    }
                case 'drivers':
                    $driverName = null;
                    if (isset($data['changes']['driver_name'])) {
                        $driverName = $data['changes']['driver_name'];
                    } elseif (isset($data['changes']['member_name'])) {
                        $driverName = $data['changes']['member_name'];
                    }
                    
                    if ($driverName) {
                        return redirect()->route('drivers.index', ['search' => $driverName]);
                    } else {
                        return redirect()->route('drivers.index');
                    }
                case 'delegations':
                    if ($delegationId) {
                        try {
                            $delegation = \App\Models\Delegation::find($delegationId);
                            if ($delegation) {
                                return redirect()->route('delegations.show', $delegationId);
                            }
                        } catch (\Exception $e) {
                            return redirect()->route('delegations.index');
                        }
                    } else {
                        return redirect()->route('delegations.index');
                    }
                    break;
                default:
                    return redirect()->route('notifications.index');
            }
        }

        return redirect()->route('notifications.index');
    }
}
