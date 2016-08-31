<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquare\Requests\Clients\GetClientRequest;
use Webaccess\ProjectSquare\Requests\Clients\GetClientsRequest;
use Webaccess\ProjectSquare\Requests\Clients\CreateClientRequest;
use Webaccess\ProjectSquare\Requests\Clients\UpdateClientRequest;
use Webaccess\ProjectSquare\Requests\Clients\DeleteClientRequest;

class TwoPasswordsException extends \Exception {}

class ClientController extends BaseController
{
    public function clients()
    {
        return view('projectsquare::clients.index');
    }

    public function index()
    {
        return view('projectsquare::agency.clients.index', [
            'clients' => app()->make('GetClientsInteractor')->getClientsPaginatedList(10, new GetClientsRequest()),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function add()
    {
        return view('projectsquare::agency.clients.add');
    }

    public function store()
    {
        try {
            $response = app()->make('CreateClientInteractor')->execute(new CreateClientRequest([
                'name' => Input::get('name'),
                'address' => Input::get('address'),
            ]));

            $this->request->session()->flash('confirmation', trans('projectsquare::clients.add_client_success'));

            return redirect()->route('clients_edit', ['id' => $response->client->id]);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::clients.add_client_error'));
            return redirect()->route('clients_index');
        }
    }

    public function edit($clientID)
    {
        try {
            $client = app()->make('GetClientInteractor')->execute(new GetClientRequest([
                'clientID' => $clientID,
            ]));
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
            app()->make('UpdateClientInteractor')->execute(new UpdateClientRequest([
                'clientID' => Input::get('client_id'),
                'name' => Input::get('name'),
                'address' => Input::get('address'),
            ]));

            $this->request->session()->flash('confirmation', trans('projectsquare::clients.edit_client_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::clients.edit_client_error'));
        }

        return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
    }

    public function delete($clientID)
    {
        try {
            app()->make('DeleteClientInteractor')->execute(new DeleteClientRequest([
                'clientID' => $clientID,
                'requesterUserID' => $this->getUser()->id,
            ]));

            $this->request->session()->flash('confirmation', trans('projectsquare::clients.delete_client_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::clients.delete_client_error'));
        }

        return redirect()->route('clients_index');
    }

    public function add_user($clientID)
    {
        try {
            $client = app()->make('GetClientInteractor')->execute(new GetClientRequest([
                'clientID' => $clientID,
            ]));
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
            if (Input::get('password') != '' && Input::get('password') != Input::get('password_confirmation')) {
                throw new TwoPasswordsException();
            } else {
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
            }
        } catch (TwoPasswordsException $e) {
            $this->request->session()->flash('error', 'Les deux mots de passe ne correspondent pas');
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
            $client = app()->make('GetClientInteractor')->execute(new GetClientRequest([
                'clientID' => $clientID,
            ]));
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
