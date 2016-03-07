<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class TicketStatusController extends BaseController
{
    public function index()
    {
        return view('projectsquare::agency.ticket_statuses.index', [
            'ticket_statuses' => app()->make('TicketStatusManager')->getTicketStatusesPaginatedList(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function add()
    {
        return view('projectsquare::agency.ticket_statuses.add', [
        ]);
    }

    public function store()
    {
        try {
            app()->make('TicketStatusManager')->createTicketStatus(Input::get('name'));
            $this->request->session()->flash('confirmation', trans('projectsquare::ticket_statuses.add_ticket_status_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::ticket_statuses.add_ticket_status_error'));
        }

        return redirect()->route('ticket_statuses_index');
    }

    public function edit($ticketStatusID)
    {
        try {
            $ticketStatus = app()->make('TicketStatusManager')->getTicketStatus($ticketStatusID);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::ticket_statuses.add_ticket_status_error'));

            return redirect()->route('ticket_statuses_index');
        }

        return view('projectsquare::agency.ticket_statuses.edit', [
            'ticket_status' => $ticketStatus,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update()
    {
        try {
            app()->make('TicketStatusManager')->updateTicketStatus(
                Input::get('ticket_status_id'),
                Input::get('name')
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::ticket_statuses.edit_ticket_status_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::ticket_statuses.edit_ticket_status_error'));
        }

        return redirect()->route('ticket_statuses_edit', ['id' => Input::get('ticket_status_id')]);
    }

    public function delete($ticketStatusID)
    {
        try {
            app()->make('TicketStatusManager')->deleteTicketStatus($ticketStatusID);
            $this->request->session()->flash('confirmation', trans('projectsquare::ticket_statuses.delete_ticket_status_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::ticket_statuses.delete_ticket_status_error'));
        }

        return redirect()->route('ticket_statuses_index');
    }
}
