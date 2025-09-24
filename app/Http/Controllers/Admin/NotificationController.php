<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $notifications = auth()->user()->notifications()->whereNull('alert_id')->where('event_id', $currentEventId)
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
            return redirect()->back()->with('error', __db('notification_not_found'));
        }

        $notification->update(['read_at' => now()]);

        $data = $notification->data;
        $module = $data['module'] ?? null;
        $delegationId = $data['delegation_id'] ?? null;
        $submoduleId = $data['submodule_id'] ?? null;

        $url = $this->getNotificationRedirectLink($delegationId, $module, $submoduleId, $data);

        if (($url)) {
            return redirect($url);
        }

        return redirect()->route('notifications.index');
    }

    public function fetchNotifications(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $notifications = [];

        $notifications  = auth()->user()->unreadNotifications()
            ->where('event_id', $currentEventId)
            ->latest()
            ->limit(5)
            ->get();

        $currentEventId = session('current_event_id', getDefaultEventId());

        $formattedNotifications = $notifications->map(function ($notification) {
            $data = is_string($notification->data)
                ? json_decode($notification->data, true)
                : $notification->data;

            $message = '';
            if (isset($data['message'])) {
                if (is_array($data['message'])) {
                    $lang = getActiveLanguage();
                    if ($lang !== 'en' && isset($data['message']['ar'])) {
                        $message = $data['message']['ar'];
                    } else {
                        $message = $data['message']['en'] ?? '';
                    }
                } else {
                    $message = $data['message'];
                }
            }

            $module = $data['module'] ?? null;
            $action = $data['action'] ?? null;
            $delegationId = $data['delegation_id'] ?? null;
            $submoduleId = $data['submodule_id'] ?? null;

            $moduleName = null;
            $moduleCode = null;
            $moduleDetails = [];

            if (isset($data['changes'])) {
                if (isset($data['changes']['escort_name'])) {
                    $moduleName = $data['changes']['escort_name'];
                } elseif (isset($data['changes']['driver_name'])) {
                    $moduleName = $data['changes']['driver_name'];
                } elseif (isset($data['changes']['member_name'])) {
                    $moduleName = $data['changes']['member_name'];
                } elseif (isset($data['changes']['delegation_code'])) {
                    $moduleCode = $data['changes']['delegation_code'];
                } elseif (isset($data['changes']['code'])) {
                    $moduleCode = $data['changes']['code'];
                }

                foreach ($data['changes'] as $key => $value) {
                    if (in_array($key, ['escort_name', 'driver_name', 'member_name', 'delegation_code', 'code', 'title'])) {
                        continue;
                    }

                    $displayValue = is_array($value)
                        ? (isset($value['new'])
                            ? $value['new']
                            : json_encode($value))
                        : $value;
                    if (!empty($displayValue) && $displayValue !== 'N/A') {
                        $moduleDetails[$key] = $displayValue;
                    }
                }
            }

            $url = $this->getNotificationRedirectLink($delegationId, $module, $submoduleId, $data);

            return [
                'id' => $notification->id,
                'module' => $module ?? __db('notification'),
                'message' => $message,
                'module_name' => $moduleName,
                'module_code' => $moduleCode,
                'module_details' => $moduleDetails,
                'created_at' => $notification->created_at->diffForHumans(),
                'url' => $url,
                'read_at' => $notification->read_at,
            ];
        });

        return response()->json([
            'success' => true,
            'notifications' => $formattedNotifications,
            'unread_count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }


    public function getUnreadCount(Request $request)
    {
        try {
            $currentEventId = session('current_event_id', getDefaultEventId());

            $unreadCount = auth()->user()
                ->unreadNotifications()
                ->where('event_id', $currentEventId)
                ->count();

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching notification count',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function getNotificationRedirectLink($delegationId, $module, $submoduleId, $data = [])
    {
        $url = "";
        if ($delegationId) {
            $url = route('delegations.show', $delegationId);
        } elseif ($module && $submoduleId) {
            switch (strtolower($module)) {
                case 'escorts':
                    $url = route('escorts.index', ['id' => $submoduleId]);
                    break;
                case 'drivers':
                    $url = route('drivers.index', ['id' => $submoduleId]);
                    break;
                default:
                    $url = '#';
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
                        $url = route('escorts.index', ['search' => $escortName]);
                    } else {
                        $url = route('escorts.index');
                    }
                    break;
                case 'drivers':
                    $driverName = null;
                    if (isset($data['changes']['driver_name'])) {
                        $driverName = $data['changes']['driver_name'];
                    } elseif (isset($data['changes']['member_name'])) {
                        $driverName = $data['changes']['member_name'];
                    }

                    if ($driverName) {
                        $url = route('drivers.index', ['search' => $driverName]);
                    } else {
                        $url = route('drivers.index');
                    }
                    break;
                default:
                    $url = '#';
            }
        }

        return $url;
    }
}
