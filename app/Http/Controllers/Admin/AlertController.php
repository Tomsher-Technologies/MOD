<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\AlertRecipient;
use App\Models\Event;
use App\Models\EventUserRole;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\AlertNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:add_alerts|hotel_add_alerts|delegate_add_alerts|escort_add_alerts|driver_add_alerts|top-management_manage_alerts', [
            'only' => ['store', 'create']
        ]);
    }
    public function index(Request $request)
    {
        $currentEventId = session('current_event_id', getDefaultEventId());

        $alerts = Alert::with('creator')->where('event_id', ($currentEventId))->latest()->paginate(10);

        return view('admin.alerts.index', compact('alerts'));
    }

    public function create()
    {
        $currentEventId = session('current_event_id', getDefaultEventId() ?? null);

        if (!$currentEventId) {
            return redirect()->back()->with('error', __db('no_current_event'));
        }

        $event = Event::findOrFail($currentEventId);

        $assignedUsers = EventUserRole::with('user')
            ->where('event_id', $event->id)
            ->get()
            ->pluck('user')
            ->unique('id');

        $modules = [
            'delegate' => 'Delegate Module',
            'escort' => 'Escort Module',
            'driver' => 'Driver Module',
            'hotel' => 'Hotel Module'
        ];

        return view('admin.alerts.create', compact('assignedUsers', 'modules', 'event'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'message' => 'required|string',
            'message_ar' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',
            'recipient_type' => 'required|in:all,module,users',
            'module' => 'exclude_unless:recipient_type,module|required|in:delegate,escort,driver,hotel',
            'users' => 'exclude_unless:recipient_type,users|required|array',
            'users.*' => 'exclude_unless:recipient_type,users|required|exists:users,id'
        ]);

        $currentEventId = session('current_event_id', getDefaultEventId() ?? null);

        if (!$currentEventId) {
            return redirect()->back()->with('error',  __db('no_current_event'));
        }

        $event = Event::findOrFail($currentEventId);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('alerts', 'public');
        }

        $multilingualMessage = [
            'en' => $request->message,
            'ar' => $request->message_ar
        ];

        $multilingualTitle = [
            'en' => $request->title,
            'ar' => $request->title_ar
        ];

        $alert = Alert::create([
            'title' => $multilingualTitle,
            'message' => $multilingualMessage,
            'event_id' => $currentEventId,
            'attachment' => $attachmentPath,
            'send_to_all' => $request->recipient_type === 'all',
            'created_by' => auth()->id()
        ]);

        if ($request->recipient_type === 'all') {
            $recipients = EventUserRole::with('user')
                ->where('event_id', $event->id)
                ->get()
                ->pluck('user')
                ->unique('id');
        } elseif ($request->recipient_type === 'module') {
            $recipients = EventUserRole::with('user')
                ->where('event_id', $event->id)
                ->where('module', $request->module)
                ->get()
                ->pluck('user')
                ->unique('id');
        } else {
            $eventUserIds = EventUserRole::where('event_id', $event->id)
                ->pluck('user_id');

            $selectedUserIds = array_intersect($request->users, $eventUserIds->toArray());

            $recipients = User::whereIn('id', $selectedUserIds)->get();
        }

        foreach ($recipients as $recipient) {
            AlertRecipient::create([
                'alert_id' => $alert->id,
                'user_id' => $recipient->id
            ]);

            $notificationData = [
                'delegation_id' => null,
                'message' => $multilingualMessage,
                'title' => $multilingualTitle,
                'module' => 'Alert',
                'action' => 'alert',
                'changes' => [
                    'title' => $multilingualTitle
                ],
                'created_at' => now(),
                'alert_id' => $alert->id,
                'event_id' => $currentEventId
            ];

            $recipient->notify(new AlertNotification($notificationData));
        }

        return redirect()->route('alerts.index')->with('success', __db('created_successfully'));
    }

    public function show(Alert $alert)
    {
        $this->markAsRead(request(), $alert->id);

        return view('admin.alerts.show', compact('alert'));
    }

    public function getLatest(Request $request)
    {
        try {
            $currentEventId = session('current_event_id', getDefaultEventId());

            $alerts = auth()->user()->notifications()->whereNotNull('alert_id')->where('event_id', $currentEventId)->latest()->limit(5)->get();

            $unreadCount = auth()->user()->notifications()->whereNotNull('alert_id')->where('event_id', $currentEventId)->whereNull('read_at')->count();

            if ($alerts && $alerts->count() > 0) {
                $alertsData = [];

                foreach ($alerts as $notification) {
                    $alertId = $notification->alert_id ?? null;

                    if ($alertId) {
                        $alert = Alert::find($alertId);


                        if ($alert) {
                            $alertData = [
                                'id' => $alert->id,
                                'title' => is_array($alert->title)
                                    ? ($alert->title[app()->getLocale()] ?? $alert->title['en'] ?? '')
                                    : $alert->title,
                                'message' => is_array($alert->message)
                                    ? ($alert->message[app()->getLocale()] ?? $alert->message['en'] ?? '')
                                    : $alert->message,
                                'created_at' => $alert->created_at->format('Y-m-d H:i:s'),
                                'notification_id' => $notification->id
                            ];

                            $alertsData[] = $alertData;

                            $this->markAsRead($request, $notification->id);
                        }
                    }
                }

                if (!empty($alertsData)) {
                    return response()->json([
                        'success' => true,
                        'alerts' => $alertsData,
                        'unread_count' => $unreadCount
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'No alerts found'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching latest alerts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead(Request $request, $id)
    {

        $alertRecipient = AlertRecipient::where('alert_id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($alertRecipient && !$alertRecipient->read_at) {
            $alertRecipient->update(['read_at' => now()]);
        }

        $currentEventId = session('current_event_id', getDefaultEventId());

        $notification = auth()->user()->notifications()
            ->where('event_id', $currentEventId)
            ->where('alert_id', $id)
            ->first();

        if ($notification && !$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    public function getUnreadCount(Request $request)
    {
        try {

            $currentEventId = session('current_event_id', getDefaultEventId());

            $modelCount = auth()->user()->notifications()->whereNotNull('alert_id')->where('event_id', $currentEventId)->whereNull('read_at')->count();

            return response()->json([
                'success' => true,
                'unread_count' => $modelCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching alert count',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
