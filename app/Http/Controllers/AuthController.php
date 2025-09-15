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
        $user = \App\Models\User::where('username', $request->username)->orWhere('email', $request->username)->first();

        $rules = [
            'username' => 'required',
            'password' => 'required|string',
        ];

        if ($user && !in_array($user->user_type, ['admin', 'staff'])) {
            $rules['event_id'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules, [
            'username.required' => __db('username_required'),
            'password.required' => __db('password_required'),
            'event_id.required' => __db('event_required'),
        ]);
        echo "<pre>";print_r($validator->errors());exit;

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('username', 'password');
        $eventId = $request->input('event_id');

        $login = $request->input('username'); 
        $password = $request->input('password');
        $eventId = $request->input('event_id');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $login, 'password' => $password])) {
            $user = Auth::user();

            if($user->user_type == 'admin' || $user->user_type == 'staff') {
                $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
                $user->givePermissionTo($rolePermissions);
            }else{
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
            }
            
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
