<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class ClientController extends BaseController
{
    public function clients()
    {
        return view('projectsquare::clients.index', [
        ]);
    }

    public function index()
    {
        return view('projectsquare::agency.clients.index', [
            'clients' => app()->make('ClientManager')->getClientsPaginatedList(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function add()
    {
        return view('projectsquare::agency.clients.add', [
        ]);
    }

    public function store()
    {
        try {
            $clientID = app()->make('ClientManager')->createClient(Input::get('name'), Input::get('address'));
            $this->request->session()->flash('confirmation', trans('projectsquare::clients.add_client_success'));

            return redirect()->route('clients_edit', ['id' => $clientID]);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::clients.add_client_error'));
            return redirect()->route('clients_index');
        }
    }

    public function edit($clientID)
    {
        try {
            $client = app()->make('ClientManager')->getClient($clientID);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());

            return redirect()->route('clients_index');
        }

        return view('projectsquare::agency.clients.edit', [
            'client' => $client,
            'users' => app()->make('UserManager')->getUsersByClient($clientID),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            app()->make('ClientManager')->updateClient(
                Input::get('client_id'),
                Input::get('name'),
                Input::get('address')
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::clients.edit_client_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::clients.edit_client_error'));
        }

        return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
    }

    public function delete($clientID)
    {
        try {
            app()->make('ClientManager')->deleteClient($clientID);
            $this->request->session()->flash('confirmation', trans('projectsquare::clients.delete_client_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::clients.delete_client_error'));
        }

        return redirect()->route('clients_index');
    }

    public function add_user($clientID)
    {
        try {
            $client = app()->make('ClientManager')->getClient($clientID);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());

            return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
        }

        return view('projectsquare::agency.clients.users.add', [
            'client' => $client
        ]);
    }

    public function store_user()
    {
        try {
            app()->make('UserManager')->createUser(
                Input::get('first_name'),
                Input::get('last_name'),
                Input::get('email'),
                Input::get('password'),
                Input::get('mobile'),
                Input::get('phone'),
                Input::get('client_id'),
                Input::get('client_role'),
                false
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::users.add_user_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::users.add_user_error'));
        }

        return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
    }


    public function edit_user($clientID, $userID)
    {
        try {
            $user = app()->make('UserManager')->getUser($userID);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::users.user_not_found'));

            return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
        }

        try {
            $client = app()->make('ClientManager')->getClient($clientID);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());

            return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
        }

        return view('projectsquare::agency.clients.users.edit', [
            'user' => $user,
            'client' => $client
        ]);
    }

    public function update_user()
    {
        try {
            app()->make('UserManager')->updateUser(
                Input::get('user_id'),
                Input::get('first_name'),
                Input::get('last_name'),
                Input::get('email'),
                Input::get('password'),
                Input::get('mobile'),
                Input::get('phone'),
                Input::get('client_id'),
                Input::get('client_role'),
                false
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::users.edit_user_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::users.edit_user_error'));
        }

        return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
    }

    public function delete_user($clientID, $userID)
    {
        try {
            app()->make('UserManager')->deleteUser($userID);
            $this->request->session()->flash('confirmation', trans('projectsquare::users.delete_user_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::users.delete_user_error'));
        }

        return redirect()->route('clients_edit', ['id' => $clientID]);
    }
}
