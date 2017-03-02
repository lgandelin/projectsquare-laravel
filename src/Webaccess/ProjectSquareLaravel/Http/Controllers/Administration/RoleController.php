<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Administration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class RoleController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::administration.roles.index', [
            'roles' => app()->make('RoleManager')->getRolesPaginatedList(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::administration.roles.add', [
        ]);
    }

    public function store(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('RoleManager')->createRole(Input::get('name'));
            $request->session()->flash('confirmation', trans('projectsquare::roles.add_role_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::roles.add_role_error'));
        }

        return redirect()->route('roles_index');
    }

    public function edit(Request $request)
    {
        parent::__construct($request);

        $roleID = $request->id;

        try {
            $role = app()->make('RoleManager')->getRole($roleID);
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('roles_index');
        }

        return view('projectsquare::administration.roles.edit', [
            'role' => $role,
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('RoleManager')->updateRole(
                Input::get('role_id'),
                Input::get('name')
            );
            $request->session()->flash('confirmation', trans('projectsquare::roles.edit_role_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::roles.edit_role_error'));
        }

        return redirect()->route('roles_edit', ['id' => Input::get('role_id')]);
    }

    public function delete(Request $request)
    {
        parent::__construct($request);

        $roleID = $request->id;

        try {
            app()->make('RoleManager')->deleteRole($roleID);
            $request->session()->flash('confirmation', trans('projectsquare::roles.delete_role_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::roles.delete_role_error'));
        }

        return redirect()->route('roles_index');
    }
}
