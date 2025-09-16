<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\AlertRecipient;
use App\Models\Event;
use App\Models\EventUserRole;
use App\Models\User;
use App\Notifications\AlertNotification;
use Illuminate\Http\Request;

class AlertController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:add_alerts', [
            'only' => ['store', 'create']
        ]);
    }
    public function index(Request $request)
    {
        $alerts = Alert::with('creator')->latest()->paginate(10);

        return view('admin.alerts.index', compact('alerts'));
    }

    public function create()
    {
        $currentEventId = session('current_event_id', getDefaultEventId() ?? null);
        
        if (!$currentEventId) {
            return redirect()->back()->with('error', 'No current event selected.');
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
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'recipient_type' => 'required|in:all,module,users',
            'module' => 'required_if:recipient_type,module|in:delegate,escort,driver,hotel',
            'users' => 'required_if:recipient_type,users|array',
            'users.*' => 'required_if:recipient_type,users|exists:users,id'
        ]);

        $currentEventId = session('current_event_id', getDefaultEventId() ?? null);
        
        if (!$currentEventId) {
            return redirect()->back()->with('error', 'No current event selected.');
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
                'alert_id' => $alert->id
            ];

            $recipient->notify(new AlertNotification($notificationData));
        }

        return redirect()->route('alerts.index')->with('success', 'Alert created successfully.');
    }

    public function show(Alert $alert)
    {
        $alert->load(['alertRecipients.user']);

        $alertRecipient = AlertRecipient::where('alert_id', $alert->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($alertRecipient && !$alertRecipient->read_at) {
            $alertRecipient->update(['read_at' => now()]);
        }

        return view('admin.alerts.show', compact('alert'));
    }
}
