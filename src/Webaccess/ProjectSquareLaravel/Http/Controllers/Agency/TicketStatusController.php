<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class TicketStatusController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::agency.ticket_statuses.index', [
            'ticket_statuses' => app()->make('TicketStatusManager')->getTicketStatusesPaginatedList(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::agency.ticket_statuses.add', [
        ]);
    }

    public function store(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('TicketStatusManager')->createTicketStatus(
                Input::get('name'),
                Input::get('include_in_planning') === 'y' ? 1 : 0
            );
            $request->session()->flash('confirmation', trans('projectsquare::ticket_statuses.add_ticket_status_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::ticket_statuses.add_ticket_status_error'));
        }

        return redirect()->route('ticket_statuses_index');
    }

    public function edit(Request $request)
    {
        parent::__construct($request);

        $ticketStatusID = $request->id;

        try {
            $ticketStatus = app()->make('TicketStatusManager')->getTicketStatus($ticketStatusID);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::ticket_statuses.add_ticket_status_error'));

            return redirect()->route('ticket_statuses_index');
        }

        return view('projectsquare::agency.ticket_statuses.edit', [
            'ticket_status' => $ticketStatus,
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('TicketStatusManager')->updateTicketStatus(
                Input::get('ticket_status_id'),
                Input::get('name'),
                Input::get('include_in_planning') === 'y' ? 1 : 0
            );
            $request->session()->flash('confirmation', trans('projectsquare::ticket_statuses.edit_ticket_status_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::ticket_statuses.edit_ticket_status_error'));
        }

        return redirect()->route('ticket_statuses_edit', ['id' => Input::get('ticket_status_id')]);
    }

    public function delete(Request $request)
    {
        parent::__construct($request);

        $ticketStatusID = $request->id;

        try {
            app()->make('TicketStatusManager')->deleteTicketStatus($ticketStatusID);
            $request->session()->flash('confirmation', trans('projectsquare::ticket_statuses.delete_ticket_status_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::ticket_statuses.delete_ticket_status_error'));
        }

        return redirect()->route('ticket_statuses_index');
    }
}
