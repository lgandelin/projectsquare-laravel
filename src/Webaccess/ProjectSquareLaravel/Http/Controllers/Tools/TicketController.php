<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Webaccess\ProjectSquare\Requests\Notifications\ReadNotificationRequest;
use Webaccess\ProjectSquare\Requests\Planning\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Tickets\CreateTicketRequest;
use Webaccess\ProjectSquare\Requests\Tickets\DeleteTicketRequest;
use Webaccess\ProjectSquare\Requests\Tickets\UpdateTicketInfosRequest;
use Webaccess\ProjectSquare\Requests\Tickets\UpdateTicketRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;
use Webaccess\ProjectSquareLaravel\Tools\UploadTool;

class TicketController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        $itemsPerPage = $request->get('it') ? $request->get('it') : env('TICKETS_PER_PAGE', 10);

        $request->session()->put('tickets_interface', 'tickets');

        if (Input::get('filter_project') !== null) Session::put('tickets_filter_project', Input::get('filter_project'));
        if (Input::get('filter_status') !== null) Session::put('tickets_filter_status', Input::get('filter_status'));
        if (Input::get('filter_allocated_user') !== null) Session::put('tickets_filter_allocated_user', Input::get('filter_allocated_user'));
        if (Input::get('filter_type') !== null) Session::put('tickets_filter_type', Input::get('filter_type'));

        return view('projectsquare::tools.tickets.index', [

            //tickets variables
            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList(
                $this->getUser()->id,
                $itemsPerPage,
                Session::get('tickets_filter_project') === "na" ? null : Session::get('tickets_filter_project'),
                Session::get('tickets_filter_allocated_user') === "na" ? null : Session::get('tickets_filter_allocated_user'),
                Session::get('tickets_filter_status') === "na" ? null : Session::get('tickets_filter_status'),
                Session::get('tickets_filter_type') === "na" ? null : Session::get('tickets_filter_type'),
                $request->get('sc'),
                $request->get('so')
            ),
            'projects' => app()->make('GetProjectsInteractor')->getCurrentProjects($this->getUser()->id),
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'ticket_statuses' => app()->make('TicketStatusManager')->getTicketStatuses(),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'filters' => [
                'project' => Session::get('tickets_filter_project'),
                'allocated_user' => Session::get('tickets_filter_allocated_user'),
                'status' => Session::get('tickets_filter_status'),
                'type' => Session::get('tickets_filter_type'),
            ],
            'items_per_page' => $request->get('it') ? $request->get('it') : $itemsPerPage,
            'sort_column' => $request->get('sc'),
            'sort_order' => ($request->get('so') == 'asc') ? 'desc' : 'asc',
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,

            //planning variables
            'events' => app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => (Input::get('filter_planning_user')) ? Input::get('filter_planning_user') : $this->getUser()->id,
                'projectID' => Input::get('filter_project'),
            ])),
            'filters_planning' => [
                'project' => Input::get('filter_planning_project'),
                'user' => Input::get('filter_planning_user'),
            ],
            'userID' => (Input::get('filter_planning_user')) ? Input::get('filter_planning_user') : $this->getUser()->id,
            'currentUserID' => $this->getUser()->id,
        ]);
    }

    public function add(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::tools.tickets.add', [
            'projects' => app()->make('GetProjectsInteractor')->getCurrentProjects($this->getUser()->id),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'ticket_statuses' => app()->make('TicketStatusManager')->getTicketStatuses(),
            'users' => ($this->getCurrentProject()) ? app()->make('UserManager')->getUsersByProject($this->getCurrentProject()->id) : app()->make('UserManager')->getAgencyUsers(),
            'current_project_id' => ($this->getCurrentProject()) ? $this->getCurrentProject()->id : null,
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'data' => ($request->session()->has('data')) ? $request->session()->get('data') : null,
            'back_link' => ($request->session()->get('tickets_interface') === 'project') ? route('project_tickets', ['uuid' => $this->getCurrentProject()->id]) : route('tickets_index')
        ]);
    }

    public function store(Request $request)
    {
        parent::__construct($request);

        try {
            $data = [
                'title' => Input::get('title'),
                'projectID' => Input::get('project_id'),
                'typeID' => Input::get('type_id'),
                'description' => Input::get('description'),
                'statusID' => Input::get('status_id'),
                'authorUserID' => Input::get('author_user_id'),
                'allocatedUserID' => Input::get('allocated_user_id'),
                'priority' => Input::get('priority'),
                'dueDate' => \DateTime::createFromFormat('d/m/Y', Input::get('due_date')),
                'estimatedTimeDays' => StringTool::formatNumber(Input::get('estimated_time_days')),
                'estimatedTimeHours' => StringTool::formatNumber(Input::get('estimated_time_hours')),
                'spentTimeDays' => 0,
                'spentTimeHours' => 0,
                'comments' => Input::get('comments'),
                'requesterUserID' => $this->getUser()->id,
            ];
            $request->session()->flash('data', $data);
            $response = app()->make('CreateTicketInteractor')->execute(new CreateTicketRequest($data));

            $request->session()->flash('confirmation', trans('projectsquare::tickets.add_ticket_success'));

            if ($this->isUserAClient())
                return redirect()->route('project_tickets', ['uuid' => $this->getCurrentProject()->id]);

            return ($request->session()->get('tickets_interface') === 'project') ? redirect()->route('project_tickets', ['uuid' => $this->getCurrentProject()->id]) : redirect()->route('tickets_index');
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('tickets_add');
    }

    public function edit(Request $request)
    {
        parent::__construct($request);

        $ticketID = $request->uuid;

        //Read linked notification
        $notifications = $this->getUnreadNotifications();
        if (is_array($notifications) && sizeof($notifications) > 0) {
            foreach ($notifications as $notification) {
                if ($notification->entityID == $ticketID) {
                    app()->make('ReadNotificationInteractor')->execute(new ReadNotificationRequest([
                        'notificationID' => $notification->id,
                        'userID' => $this->getUser()->id,
                    ]));
                }
            }
        }

        try {
            $ticket = app()->make('GetTicketInteractor')->getTicketWithStates($ticketID, $this->getUser()->id);
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('tickets_index');
        }

        return view('projectsquare::tools.tickets.edit', [
            'ticket' => $ticket,
            'projects' => app()->make('GetProjectsInteractor')->getCurrentProjects($this->getUser()->id),
            'ticket_states' => app()->make('GetTicketInteractor')->getTicketStatesPaginatedList($ticketID, env('TICKET_STATES_PER_PAGE', 10)),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'ticket_status' => app()->make('TicketStatusManager')->getTicketStatuses(),
            'users' => app()->make('UserManager')->getUsersByProject($ticket->projectID),
            'files' => app()->make('FileManager')->getFilesByTicket($ticketID),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
            'back_link' => ($request->session()->get('tickets_interface') === 'project') ? route('project_tickets', ['uuid' => $this->getCurrentProject()->id]) : route('tickets_index')
        ]);
    }

    public function updateInfos(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('UpdateTicketInfosInteractor')->execute(new UpdateTicketInfosRequest([
                'ticketID' => Input::get('ticket_id'),
                'title' => Input::get('title'),
                'projectID' => Input::get('project_id'),
                'typeID' => Input::get('type_id'),
                'description' => Input::get('description'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::tickets.edit_ticket_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::tickets.edit_ticket_error'));
        }

        if ($request->session()->get('tickets_interface') === 'project')
            return redirect()->route('project_tickets_edit', ['uuid' => $this->getCurrentProject()->id, 'ticket_uuid' => Input::get('ticket_id')]);

        return redirect()->route('tickets_edit', ['id' => Input::get('ticket_id')]);
    }

    public function update(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('UpdateTicketInteractor')->execute(new UpdateTicketRequest([
                'ticketID' => Input::get('ticket_id'),
                'statusID' => Input::get('status_id'),
                'authorUserID' => Input::get('author_user_id'),
                'allocatedUserID' => Input::get('allocated_user_id'),
                'priority' => Input::get('priority'),
                'dueDate' => \DateTime::createFromFormat('d/m/Y', Input::get('due_date')),
                'estimatedTimeDays' => StringTool::formatNumber(Input::get('estimated_time_days')),
                'estimatedTimeHours' => StringTool::formatNumber(Input::get('estimated_time_hours')),
                'spentTimeDays' => StringTool::formatNumber(Input::get('spent_time_days')),
                'spentTimeHours' => StringTool::formatNumber(Input::get('spent_time_hours')),
                'comments' => Input::get('comments'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::tickets.edit_ticket_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        if ($request->session()->get('tickets_interface') === 'project')
            return redirect()->route('project_tickets_edit', ['uuid' => $this->getCurrentProject()->id, 'ticket_uuid' => Input::get('ticket_id')]);

        return redirect()->route('tickets_edit', ['id' => Input::get('ticket_id')]);
    }

    public function delete(Request $request)
    {
        parent::__construct($request);

        $ticketID = $request->uuid;

        try {
            app()->make('DeleteTicketInteractor')->execute(new DeleteTicketRequest([
                'ticketID' => $ticketID,
                'requesterUserID' => $this->getUser()->id,
            ]));
            $request->session()->flash('confirmation', trans('projectsquare::tickets.delete_ticket_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        if ($this->isUserAClient())
            return redirect()->route('project_tickets', ['uuid' => $this->getCurrentProject()->id]);
        
        return redirect()->route('tickets_index');
    }

    public function upload_file()
    {
        try {
            if ($data = UploadTool::uploadFileForTicket(
                Input::file('files'),
                Input::get('ticket_id')
            )) {
                $fileID = app()->make('FileManager')->createFile(
                    $data->name,
                    $data->path,
                    $data->thumbnailPath,
                    $data->mimeType,
                    $data->size,
                    Input::get('ticket_id')
                );

                $data->deleteUrl = action('\Webaccess\ProjectSquareLaravel\Http\Controllers\Tools\TicketController@delete_file', ['id' => $fileID]);
                $data->deleteType = 'GET';
            }

            return response()->json(['files' => [$data]], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete_file(Request $request)
    {
        parent::__construct($request);

        $fileID = $request->id;

        try {
            app()->make('FileManager')->deleteFile($fileID);
            $request->session()->flash('confirmation', trans('projectsquare::files.delete_file_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::files.delete_file_error'));
        }

        return redirect()->back();
    }

    public function unallocate(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('UpdateTicketInteractor')->execute(new UpdateTicketRequest([
                'ticketID' => Input::get('ticket_id'),
                'requesterUserID' => $this->getUser()->id,
                'allocatedUserID' => null
            ]));

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
