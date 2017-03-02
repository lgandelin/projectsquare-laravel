<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Administration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquare\Requests\Clients\GetClientsRequest;

class TwoPasswordsException extends \Exception {}

class UserController extends BaseController
{
    public function users(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::users.index', [
        ]);
    }

    public function index(Request $request)
    {
        parent::__construct($request);
        
        return view('projectsquare::administration.users.index', [
            'users' => app()->make('UserManager')->getAgencyUsersPaginatedList(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        parent::__construct($request);
        
        return view('projectsquare::administration.users.add', [
            'clients' => app()->make('GetClientsInteractor')->execute(new GetClientsRequest()),
        ]);
    }

    public function store(Request $request)
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
                    null,
                    null,
                    null,
                    null,
                    (Input::get('is_administrator') == 'y') ? true : false
                );
                $request->session()->flash('confirmation', trans('projectsquare::users.add_user_success'));
            }
        } catch (TwoPasswordsException $e) {
            $request->session()->flash('error', 'Les deux mots de passe ne correspondent pas');
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('users_index');
    }

    public function edit(Request $request)
    {
        parent::__construct($request);

        $userID = $request->uuid;

        try {
            $user = app()->make('UserManager')->getUser($userID);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::users.user_not_found'));

            return redirect()->route('users_index');
        }

        return view('projectsquare::administration.users.edit', [
            'user' => $user,
            'clients' => app()->make('GetClientsInteractor')->execute(new GetClientsRequest()),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update(Request $request)
    {
        parent::__construct($request);
        
        try {
            app()->make('UserManager')->updateUser(
                Input::get('user_id'),
                Input::get('first_name'),
                Input::get('last_name'),
                Input::get('email'),
                Input::get('password'),
                null,
                null,
                null,
                null,
                (Input::get('is_administrator') == 'y') ? true : false
            );
            $request->session()->flash('confirmation', trans('projectsquare::users.edit_user_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('users_edit', ['id' => Input::get('user_id')]);
    }

    public function generate_password(Request $request)
    {
        parent::__construct($request);

        $userID = $request->uuid;

        app()->make('UserManager')->generateNewPassword($userID);
        $request->session()->flash('confirmation', trans('projectsquare::users.password_generated_success'));

        return redirect()->route('users_edit', ['id' => Input::get('user_id')]);
    }

    public function delete(Request $request)
    {
        parent::__construct($request);

        $userID = $request->uuid;
        
        try {
            app()->make('UserManager')->deleteUser($userID);
            $request->session()->flash('confirmation', trans('projectsquare::users.delete_user_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::users.delete_user_error'));
        }

        return redirect()->route('users_index');
    }
}
