<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $eventId = $request->input('event_id');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $roleAssignment = EventUserRole::where('user_id', $user->id)
                ->where('event_id', $eventId)
                ->first();

            if (!$roleAssignment) {
                Auth::logout();
                return back()->withErrors(['You are not assigned to the selected event.']);
            }

            session(['current_event_id' => $eventId]);
            session(['current_module' => $roleAssignment->module]);

            return redirect()->route($roleAssignment->module . '.dashboard');
        }

        return back()->withErrors(['Invalid credentials']);
    }

}
