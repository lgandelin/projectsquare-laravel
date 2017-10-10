<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return view('projectsquare::config.index', []);
    }

    public function config_handler()
    {
        $email = $this->request->email;
        $password = $this->request->password;

        app()->make('UserManager')->createUser(
            $this->request->first_name,
            $this->request->last_name,
            $email,
            $password,
            null,
            null,
            null,
            null,
            1,
            true
        );

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('config');
    }

    public function confirmation()
    {
        return view('projectsquare::config.confirmation', []);
    }
}