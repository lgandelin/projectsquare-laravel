<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Notifications\ReadNotificationRequest;
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

        $request->session()->put('tickets_interface', 'tickets');

        return view('projectsquare::tools.tickets.index', [
            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList(
                $this->getUser()->id,
                env('TICKETS_PER_PAGE', 10),
                Input::get('filter_project'),
                Input::get('filter_allocated_user'),
                Input::get('filter_status'),
                Input::get('filter_type')
            ),
            'projects' => app()->make('GetProjectsInteractor')->getProjects($this->getUser()->id),
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'ticket_statuses' => app()->make('TicketStatusManager')->getTicketStatuses(),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'filters' => [
                'project' => Input::get('filter_project'),
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
                'type' => Input::get('filter_type'),
            ],
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::tools.tickets.add', [
            'projects' => app()->make('GetProjectsInteractor')->getProjects($this->getUser()->id),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'ticket_status' => app()->make('TicketStatusManager')->getTicketStatuses(),
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
            return redirect()->route('tickets_index');
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
            'projects' => app()->make('GetProjectsInteractor')->getProjects($this->getUser()->id),
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
