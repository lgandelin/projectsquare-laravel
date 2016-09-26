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
        $email = $this->request->get('email');
        $password = $this->request->get('password');

        app()->make('UserManager')->createUser(
            $this->request->get('first_name'),
            $this->request->get('last_name'),
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
            return redirect()->route('dashboard');
        }

        return redirect()->route('config');
    }

    public function confirmation()
    {
        return view('projectsquare::config.confirmation', []);
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