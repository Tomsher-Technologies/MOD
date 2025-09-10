<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventUserRole;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $events = Event::orderByDesc('is_default')->get();
        return view('frontend.auth.login', compact('events'));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|string',
            'event_id'  => 'required'
        ], [
            'email.required'     => __db('email_required'),
            'email.email'        => __db('valid_email'),
            'password.required'  => __db('password_required'),
            'event_id.required'  => __db('event_required'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        $eventId = $request->input('event_id');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $roleAssignment = EventUserRole::where('user_id', $user->id)
                ->where('event_id', $eventId)
                ->first();

            if (!$roleAssignment) {
                Auth::logout();
                return back()->withErrors(['password' => __db('event_not_assigned')]);
            }
            $role = $roleAssignment->role?->name;
            $user->syncRoles($role);

            $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

            $user->syncPermissions([]);

            $event = $roleAssignment->event;

            if ($event?->status == 1) {
                $filtered = collect($rolePermissions)->filter(function ($perm) {
                    return str_contains($perm, '_view_') || str_contains($perm, '_manage_');
                })->toArray();

                $user->givePermissionTo($filtered);
            } else {
                $user->givePermissionTo($rolePermissions);
            }

            session(['current_event_id' => $eventId]);
            session(['current_module' => $roleAssignment->module]);

            // return redirect()->route($roleAssignment->module . '.dashboard');
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['password' => __db('invalid_credentials')]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }

}
