<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Administration;

use Illuminate\Http\Request;
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
    public function index(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::administration.clients.index', [
            'clients' => app()->make('GetClientsInteractor')->getClientsPaginatedList(10, new GetClientsRequest()),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::administration.clients.add');
    }

    public function store(Request $request)
    {
        parent::__construct($request);

        try {
            $response = app()->make('CreateClientInteractor')->execute(new CreateClientRequest([
                'name' => Input::get('name'),
                'address' => Input::get('address'),
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::clients.add_client_success'));

            return redirect()->route('clients_edit', ['id' => $response->client->id]);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::clients.add_client_error'));
            return redirect()->route('clients_index');
        }
    }

    public function store_ajax(Request $request)
    {
        parent::__construct($request);

        $success = false;
        $error = false;

        try {
            $response = app()->make('CreateClientInteractor')->execute(new CreateClientRequest([
                'name' => Input::get('name'),
                'address' => Input::get('address'),
            ]));
            $success = true;
        } catch (\Exception $e) {
            $success = false;
            $error = trans('projectsquare::clients.add_client_error');
        }

        $parameters = [
            'success' => $success,
            'error' => $error
        ];

        if (isset($response->client) && $response->client) {
            $parameters['client_id'] = $response->client->id;
            $parameters['client_name'] = $response->client->name;
        }

        return response()->json($parameters);
    }

    public function edit(Request $request)
    {
        parent::__construct($request);

        $clientID = $request->uuid;

        try {
            $client = app()->make('GetClientInteractor')->execute(new GetClientRequest([
                'clientID' => $clientID,
            ]));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('clients_index');
        }

        return view('projectsquare::administration.clients.edit', [
            'client' => $client,
            'users' => app()->make('UserManager')->getUsersByClient($clientID),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('UpdateClientInteractor')->execute(new UpdateClientRequest([
                'clientID' => Input::get('client_id'),
                'name' => Input::get('name'),
                'address' => Input::get('address'),
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::clients.edit_client_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::clients.edit_client_error'));
        }

        return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
    }

    public function delete(Request $request)
    {
        parent::__construct($request);

        $clientID = $request->uuid;

        try {
            app()->make('DeleteClientInteractor')->execute(new DeleteClientRequest([
                'clientID' => $clientID,
                'requesterUserID' => $this->getUser()->id,
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::clients.delete_client_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::clients.delete_client_error'));
        }

        return redirect()->route('clients_index');
    }

    public function add_user(Request $request)
    {
        parent::__construct($request);

        $clientID = $request->uuid;

        try {
            $client = app()->make('GetClientInteractor')->execute(new GetClientRequest([
                'clientID' => $clientID,
            ]));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
        }

        return view('projectsquare::administration.clients.users.add', [
            'client' => $client
        ]);
    }

    public function store_user(Request $request)
    {
        parent::__construct($request);

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
                    null,
                    false
                );
                $request->session()->flash('confirmation', trans('projectsquare::users.add_user_success'));
            }
        } catch (TwoPasswordsException $e) {
            $request->session()->flash('error', 'Les deux mots de passe ne correspondent pas');
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::users.add_user_error'));
        }

        return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
    }


    public function edit_user(Request $request)
    {
        parent::__construct($request);

        $clientID = $request->uuid;
        $userID = $request->user_id;

        try {
            $user = app()->make('UserManager')->getUser($userID);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::users.user_not_found'));

            return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
        }

        try {
            $client = app()->make('GetClientInteractor')->execute(new GetClientRequest([
                'clientID' => $clientID,
            ]));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
        }

        return view('projectsquare::administration.clients.users.edit', [
            'user' => $user,
            'client' => $client
        ]);
    }

    public function update_user(Request $request)
    {
        parent::__construct($request);

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
                null,
                false
            );
            $request->session()->flash('confirmation', trans('projectsquare::users.edit_user_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::users.edit_user_error'));
        }

        return redirect()->route('clients_edit', ['id' => Input::get('client_id')]);
    }

    public function delete_user(Request $request)
    {
        parent::__construct($request);

        $clientID = $request->uuid;
        $userID = $request->user_id;

        try {
            app()->make('UserManager')->deleteUser($userID);
            $request->session()->flash('confirmation', trans('projectsquare::users.delete_user_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::users.delete_user_error'));
        }

        return redirect()->route('clients_edit', ['id' => $clientID]);
    }
}
