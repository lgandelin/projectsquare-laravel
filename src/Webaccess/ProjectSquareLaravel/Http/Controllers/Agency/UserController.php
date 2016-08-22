<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class TwoPasswordsException extends \Exception {}

class UserController extends BaseController
{
    public function users()
    {
        return view('projectsquare::users.index', [
        ]);
    }

    public function index()
    {
        return view('projectsquare::agency.users.index', [
            'users' => app()->make('UserManager')->getAgencyUsersPaginatedList(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function add()
    {
        return view('projectsquare::agency.users.add', [
            'clients' => app()->make('ClientManager')->getClients(),
        ]);
    }

    public function store()
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
                    null,
                    null,
                    null,
                    null,
                    (Input::get('is_administrator') == 'y') ? true : false
                );
                $this->request->session()->flash('confirmation', trans('projectsquare::users.add_user_success'));
            }
        } catch (TwoPasswordsException $e) {
            $this->request->session()->flash('error', 'Les deux mots de passe ne correspondent pas');
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('users_index');
    }

    public function edit($userID)
    {
        try {
            $user = app()->make('UserManager')->getUser($userID);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::users.user_not_found'));

            return redirect()->route('users_index');
        }

        return view('projectsquare::agency.users.edit', [
            'user' => $user,
            'clients' => app()->make('ClientManager')->getClients(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
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
            $this->request->session()->flash('confirmation', trans('projectsquare::users.edit_user_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('users_edit', ['id' => Input::get('user_id')]);
    }

    public function generate_password($userID)
    {
        app()->make('UserManager')->generateNewPassword($userID);
        $this->request->session()->flash('confirmation', trans('projectsquare::users.password_generated_success'));

        return redirect()->route('users_edit', ['id' => Input::get('user_id')]);
    }

    public function delete($userID)
    {
        try {
            app()->make('UserManager')->deleteUser($userID);
            $this->request->session()->flash('confirmation', trans('projectsquare::users.delete_user_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::users.delete_user_error'));
        }

        return redirect()->route('users_index');
    }
}
