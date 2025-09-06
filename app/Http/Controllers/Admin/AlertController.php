<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\AlertRecipient;
use App\Models\User;
use App\Notifications\AlertNotification;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $alerts = Alert::with('creator')->latest()->paginate(10);
        
        return view('admin.alerts.index', compact('alerts'));
    }
    
    public function create()
    {
        $users = User::all();
        return view('admin.alerts.create', compact('users'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'message' => 'required|string',
            'message_ar' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'users' => 'required|array',
            'users.*' => 'required|in:all,' . implode(',', User::pluck('id')->toArray())
        ]);
        
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
            'send_to_all' => in_array('all', $request->users),
            'created_by' => auth()->id()
        ]);
        
        if (in_array('all', $request->users)) {
            $recipients = User::all();
        } else {
            $recipients = User::whereIn('id', $request->users)->get();
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
