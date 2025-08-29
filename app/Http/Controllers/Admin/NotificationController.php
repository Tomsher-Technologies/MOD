<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

}
