<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class InstallController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function install1()
    {
        return view('projectsquare::install.install1', []);
    }

    public function install1_handler()
    {
        $this->request->session()->set('first_name', Input::get('first_name'));
        $this->request->session()->set('last_name', Input::get('last_name'));
        $this->request->session()->set('email', Input::get('email'));
        $this->request->session()->set('password', Input::get('password'));

        return redirect()->route('install2');
    }

    public function install2()
    {
        return view('projectsquare::install.install2', []);
    }

    public function install2_handler()
    {
        $email = $this->request->session()->get('email');
        $password = $this->request->session()->get('password');

        app()->make('UserManager')->createUser(
            $this->request->session()->get('first_name'),
            $this->request->session()->get('last_name'),
            $email,
            $password,
            null,
            null,
            null,
            null,
            true
        );

        $this->insertSeedsInDB();

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect()->route('install3');
        }

        return redirect()->route('install1');
    }

    public function install3()
    {
        return view('projectsquare::install.install3', []);
    }

    private function insertSeedsInDB()
    {
        //Ticket statuses
        DB::table('ticket_statuses')->insert(['name' => 'A faire']);
        DB::table('ticket_statuses')->insert(['name' => 'En cours']);
        DB::table('ticket_statuses')->insert(['name' => 'A recetter']);
        DB::table('ticket_statuses')->insert(['name' => 'A livrer en prod']);
        DB::table('ticket_statuses')->insert(['name' => 'En production']);
        DB::table('ticket_statuses')->insert(['name' => 'Archivé']);

        //Ticket types
        DB::table('ticket_types')->insert(['name' => 'Bug']);
        DB::table('ticket_types')->insert(['name' => 'Evolution']);
        DB::table('ticket_types')->insert(['name' => 'Orthographe']);
        DB::table('ticket_types')->insert(['name' => 'Question']);

        //Roles
        DB::table('roles')->insert(['name' => 'Chef de projet']);
        DB::table('roles')->insert(['name' => 'Chef de projet technique']);
        DB::table('roles')->insert(['name' => 'Développeur']);
        DB::table('roles')->insert(['name' => 'Webdesigner']);
    }
}