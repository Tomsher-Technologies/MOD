<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle($request, Closure $next)
    {
        $eventId = session('current_event_id');
        $moduleKey = session('current_module');

        $event = \App\Models\Event::findOrFail($eventId);

        $userRole = \App\Models\EventUserRole::with('role.permissions')
            ->where('user_id', auth()->id())
            ->where('event_id', $eventId)
            ->where('module', $moduleKey)
            ->first();

        if (!$userRole) {
            abort(403, 'Access denied: No role assigned.');
        }

        $permissions = $userRole->role->permissions->pluck('slug')->toArray();

        if ($event->status === 'completed') {
            $permissions = ['view_data'];
        }

        // if (!in_array($requiredPermission, $permissions)) {
        //     abort(403, 'Insufficient permission.');
        // }

        return $next($request);
    }
}
