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
            app()->make('ClientManager')->createClient(Input::get('name'));
            $this->request->session()->flash('confirmation', trans('projectsquare::clients.add_client_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::clients.add_client_error'));
        }

        return redirect()->route('clients_index');
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
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            app()->make('ClientManager')->updateClient(
                Input::get('client_id'),
                Input::get('name')
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
}
