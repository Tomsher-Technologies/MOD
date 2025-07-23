<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
            $redirectTo = match ($user->user_type) {
                'admin' => route('admin.dashboard'),
                'staff' => route('admin.dashboard'),
                default => '/user/dashboard',
            };
                     
            return redirect()->intended($redirectTo);
        }

        return back()->withErrors(['password' => __db('invalid_credentials')]);
    }

     // Logout the user
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

}
