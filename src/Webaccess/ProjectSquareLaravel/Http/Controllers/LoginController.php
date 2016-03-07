<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        return view('projectsquare::login', [
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
        ]);
    }

    public function authenticate()
    {
        if (Auth::attempt([
            'email' => Input::get('email'),
            'password' => Input::get('password'),
        ], Input::get('remember_token'))) {
            return redirect()->intended('/');
        }

        return redirect()->route('login')->with([
            'error' => trans('projectsquare::login.error_login_or_password'),
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
