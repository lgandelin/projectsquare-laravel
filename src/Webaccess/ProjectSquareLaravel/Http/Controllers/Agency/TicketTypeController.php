<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class TicketTypeController extends BaseController
{
    public function index()
    {
        return view('projectsquare::agency.ticket_types.index', [
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypesPaginatedList(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function add()
    {
        return view('projectsquare::agency.ticket_types.add', [
        ]);
    }

    public function store()
    {
        try {
            app()->make('TicketTypeManager')->createTicketType(Input::get('name'));
            $this->request->session()->flash('confirmation', trans('projectsquare::ticket_types.add_ticket_type_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::ticket_types.add_ticket_type_error'));
        }

        return redirect()->route('ticket_types_index');
    }

    public function edit($ticketTypeID)
    {
        try {
            $ticketType = app()->make('TicketTypeManager')->getTicketType($ticketTypeID);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::ticket_types.add_ticket_type_error'));

            return redirect()->route('ticket_types_index');
        }

        return view('projectsquare::agency.ticket_types.edit', [
            'ticket_type' => $ticketType,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            app()->make('TicketTypeManager')->updateTicketType(
                Input::get('ticket_type_id'),
                Input::get('name')
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::ticket_types.edit_ticket_type_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::ticket_types.edit_ticket_type_error'));
        }

        return redirect()->route('ticket_types_edit', ['id' => Input::get('ticket_type_id')]);
    }

    public function delete($ticketTypeID)
    {
        try {
            app()->make('TicketTypeManager')->deleteTicketType($ticketTypeID);
            $this->request->session()->flash('confirmation', trans('projectsquare::ticket_types.delete_ticket_type_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::ticket_types.delete_ticket_type_error'));
        }

        return redirect()->route('ticket_types_index');
    }
}
