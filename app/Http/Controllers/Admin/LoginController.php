<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\EventUserRole;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|string',
        ], [
            'email.required'     => __db('email_required'),
            'email.email'        => __db('valid_email'),
            'password.required'  => __db('password_required'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
            $user->givePermissionTo($rolePermissions);
            $redirectTo = match ($user->user_type) {
                // 'admin' => route('admin.dashboard'),
                // 'staff' => route('admin.dashboard'),
                default => route('admin.dashboard'),
            };
                     
            return redirect()->intended($redirectTo);
        }

        return back()->withErrors(['password' => __db('invalid_credentials')]);
    }

     // Logout the user
    public function logout(Request $request)
    {
        $user = Auth::user();
        Auth::logout();

        // if ($request->is('mod-admin/*') || $request->is('mod-admin')) {
        //     return redirect()->route('admin.login');
        // }
        // if(in_array($user->user_type, ['delegate', 'hotel', 'escort', 'driver'])){
        //     return redirect()->route('login');
        // }
        
        return redirect()->route('login');
    }

    public function checkUsername(Request $request)
    {
        $user = User::where('username', $request->username)->orWhere('email', $request->username)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => __db('user_not_found')]);
        }

        if (in_array($user->user_type, ['admin','staff'])) {
            return response()->json([
                'status' => true,
                'type'   => 'admin',
            ]);
        }

        // non-admin → load user events
        $events = EventUserRole::with('event')
                    ->where('user_id', $user->id)
                    ->where('status', 1)
                    ->get()
                    ->map(function ($eur) {
                        return [
                            'id'   => $eur->event->id,
                            'name' => $eur->event->name_en,
                        ];
                    });

        return response()->json([
            'status' => true,
            'type'   => 'other',
            'events' => $events,
        ]);
    }


}
