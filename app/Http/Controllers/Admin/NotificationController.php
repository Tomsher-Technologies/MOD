<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        
        return view('admin.notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return response()->json(['status' => 'success']);
    }
    
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return response()->json(['status' => 'success']);
    }
}