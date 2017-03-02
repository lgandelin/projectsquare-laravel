<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Administration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class TicketTypeController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::administration.ticket_types.index', [
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypesPaginatedList(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::administration.ticket_types.add', [
        ]);
    }

    public function store(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('TicketTypeManager')->createTicketType(Input::get('name'));
            $request->session()->flash('confirmation', trans('projectsquare::ticket_types.add_ticket_type_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::ticket_types.add_ticket_type_error'));
        }

        return redirect()->route('ticket_types_index');
    }

    public function edit(Request $request)
    {
        parent::__construct($request);

        $ticketTypeID = $request->id;
        
        try {
            $ticketType = app()->make('TicketTypeManager')->getTicketType($ticketTypeID);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::ticket_types.add_ticket_type_error'));

            return redirect()->route('ticket_types_index');
        }

        return view('projectsquare::administration.ticket_types.edit', [
            'ticket_type' => $ticketType,
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('TicketTypeManager')->updateTicketType(
                Input::get('ticket_type_id'),
                Input::get('name')
            );
            $request->session()->flash('confirmation', trans('projectsquare::ticket_types.edit_ticket_type_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::ticket_types.edit_ticket_type_error'));
        }

        return redirect()->route('ticket_types_edit', ['id' => Input::get('ticket_type_id')]);
    }

    public function delete(Request $request)
    {
        parent::__construct($request);

        $ticketTypeID = $request->id;

        try {
            app()->make('TicketTypeManager')->deleteTicketType($ticketTypeID);
            $request->session()->flash('confirmation', trans('projectsquare::ticket_types.delete_ticket_type_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::ticket_types.delete_ticket_type_error'));
        }

        return redirect()->route('ticket_types_index');
    }
}
