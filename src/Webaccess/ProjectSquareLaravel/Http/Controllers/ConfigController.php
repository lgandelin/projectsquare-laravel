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
        DB::table('ticket_statuses')->insert(['name' => 'A faire', 'include_in_planning' => true]);
        DB::table('ticket_statuses')->insert(['name' => 'En cours', 'include_in_planning' => true]);
        DB::table('ticket_statuses')->insert(['name' => 'A recetter', 'include_in_planning' => true]);
        DB::table('ticket_statuses')->insert(['name' => 'A livrer en prod', 'include_in_planning' => true]);
        DB::table('ticket_statuses')->insert(['name' => 'En production', 'include_in_planning' => true]);
        DB::table('ticket_statuses')->insert(['name' => 'Archivé', 'include_in_planning' => false]);

        //Ticket types
        DB::table('ticket_types')->insert(['name' => 'Bug']);
        DB::table('ticket_types')->insert(['name' => 'Evolution']);
        DB::table('ticket_types')->insert(['name' => 'Orthographe']);
        DB::table('ticket_types')->insert(['name' => 'Question']);

        //Roles
        DB::table('roles')->insert(['name' => 'Chef de projet']);
        DB::table('roles')->insert(['name' => 'Chef de projet technique']);
        DB::table('roles')->insert(['name' => 'Développeur']);
        DB::table('roles')->insert(['name' => 'Web designer']);
    }
}