<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class RoleController extends BaseController
{
    public function index()
    {
        return view('projectsquare::agency.roles.index', [
            'roles' => app()->make('RoleManager')->getRolesPaginatedList(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function add()
    {
        return view('projectsquare::agency.roles.add', [
        ]);
    }

    public function store()
    {
        try {
            app()->make('RoleManager')->createRole(Input::get('name'));
            $this->request->session()->flash('confirmation', trans('projectsquare::roles.add_role_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::roles.add_role_error'));
        }

        return redirect()->route('roles_index');
    }

    public function edit($roleID)
    {
        try {
            $role = app()->make('RoleManager')->getRole($roleID);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());

            return redirect()->route('roles_index');
        }

        return view('projectsquare::agency.roles.edit', [
            'role' => $role,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            app()->make('RoleManager')->updateRole(
                Input::get('role_id'),
                Input::get('name')
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::roles.edit_role_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::roles.edit_role_error'));
        }

        return redirect()->route('roles_edit', ['id' => Input::get('role_id')]);
    }

    public function delete($roleID)
    {
        try {
            app()->make('RoleManager')->deleteRole($roleID);
            $this->request->session()->flash('confirmation', trans('projectsquare::roles.delete_role_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::roles.delete_role_error'));
        }

        return redirect()->route('roles_index');
    }
}
