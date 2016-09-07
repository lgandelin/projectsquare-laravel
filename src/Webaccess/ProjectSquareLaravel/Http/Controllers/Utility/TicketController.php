<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Utility;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Tickets\CreateTicketRequest;
use Webaccess\ProjectSquare\Requests\Tickets\DeleteTicketRequest;
use Webaccess\ProjectSquare\Requests\Tickets\UpdateTicketInfosRequest;
use Webaccess\ProjectSquare\Requests\Tickets\UpdateTicketRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquareLaravel\Tools\UploadTool;

class TicketController extends BaseController
{
    public function index()
    {
        return view('projectsquare::tickets.index', [
            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList(
                $this->getUser()->id,
                env('TICKETS_PER_PAGE', 10),
                Input::get('filter_project'),
                Input::get('filter_allocated_user'),
                Input::get('filter_status'),
                Input::get('filter_type')
            ),
            'projects' => app()->make('ProjectManager')->getUserProjects($this->getUser()->id),
            'users' => app()->make('UserManager')->getUsers(),
            'ticket_statuses' => app()->make('TicketStatusManager')->getTicketStatuses(),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'filters' => [
                'project' => Input::get('filter_project'),
                'allocated_user' => Input::get('filter_allocated_user'),
                'status' => Input::get('filter_status'),
                'type' => Input::get('filter_type'),
            ],
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function add()
    {
        return view('projectsquare::tickets.add', [
            'projects' => app()->make('ProjectManager')->getProjects(),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'ticket_status' => app()->make('TicketStatusManager')->getTicketStatuses(),
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'current_project_id' => ($this->getCurrentProject()) ? $this->getCurrentProject()->id : null,
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'data' => ($this->request->session()->has('data')) ? $this->request->session()->get('data') : null
        ]);
    }

    public function store()
    {
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
                'estimatedTimeDays' => Input::get('estimated_time_days'),
                'estimatedTimeHours' => Input::get('estimated_time_hours'),
                'spentTimeDays' => Input::get('spent_time_days'),
                'spentTimeHours' => Input::get('spent_time_hours'),
                'comments' => Input::get('comments'),
                'requesterUserID' => $this->getUser()->id,
            ];
            $this->request->session()->flash('data', $data);
            $response = app()->make('CreateTicketInteractor')->execute(new CreateTicketRequest($data));

            $this->request->session()->flash('confirmation', trans('projectsquare::tickets.add_ticket_success'));
            return redirect()->route('tickets_index');
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('tickets_add');
    }

    public function edit($ticketID)
    {
        try {
            $ticket = app()->make('GetTicketInteractor')->getTicketWithStates($ticketID, $this->getUser()->id);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());

            return redirect()->route('tickets_index');
        }

        return view('projectsquare::tickets.edit', [
            'ticket' => $ticket,
            'projects' => app()->make('ProjectManager')->getProjects(),
            'ticket_states' => app()->make('GetTicketInteractor')->getTicketStatesPaginatedList($ticketID, env('TICKET_STATES_PER_PAGE', 10)),
            'ticket_types' => app()->make('TicketTypeManager')->getTicketTypes(),
            'ticket_status' => app()->make('TicketStatusManager')->getTicketStatuses(),
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'files' => app()->make('FileManager')->getFilesByTicket($ticketID),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function updateInfos()
    {
        try {
            app()->make('UpdateTicketInfosInteractor')->execute(new UpdateTicketInfosRequest([
                'ticketID' => Input::get('ticket_id'),
                'title' => Input::get('title'),
                'projectID' => Input::get('project_id'),
                'typeID' => Input::get('type_id'),
                'description' => Input::get('description'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $this->request->session()->flash('confirmation', trans('projectsquare::tickets.edit_ticket_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::tickets.edit_ticket_error'));
        }

        return redirect()->route('tickets_edit', ['id' => Input::get('ticket_id')]);
    }

    public function update()
    {
        try {
            app()->make('UpdateTicketInteractor')->execute(new UpdateTicketRequest([
                'ticketID' => Input::get('ticket_id'),
                'statusID' => Input::get('status_id'),
                'authorUserID' => Input::get('author_user_id'),
                'allocatedUserID' => Input::get('allocated_user_id'),
                'priority' => Input::get('priority'),
                'dueDate' => \DateTime::createFromFormat('d/m/Y', Input::get('due_date')),
                'estimatedTimeDays' => Input::get('estimated_time_days'),
                'estimatedTimeHours' => Input::get('estimated_time_hours'),
                'spentTimeDays' => Input::get('spent_time_days'),
                'spentTimeHours' => Input::get('spent_time_hours'),
                'comments' => Input::get('comments'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $this->request->session()->flash('confirmation', trans('projectsquare::tickets.edit_ticket_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('tickets_edit', ['id' => Input::get('ticket_id')]);
    }

    public function delete($ticketID)
    {
        try {
            app()->make('DeleteTicketInteractor')->execute(new DeleteTicketRequest([
                'ticketID' => $ticketID,
                'requesterUserID' => $this->getUser()->id,
            ]));
            $this->request->session()->flash('confirmation', trans('projectsquare::tickets.delete_ticket_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
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

                $data->deleteUrl = action('\Webaccess\ProjectSquareLaravel\Http\Controllers\TicketController@delete_file', ['id' => $fileID]);
                $data->deleteType = 'GET';
            }

            return response()->json(['files' => [$data]], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete_file($fileID)
    {
        try {
            app()->make('FileManager')->deleteFile($fileID);
            $this->request->session()->flash('confirmation', trans('projectsquare::files.delete_file_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::files.delete_file_error'));
        }

        return redirect()->back();
    }

    public function unallocate()
    {
        try {
            app()->make('UpdateTicketInteractor')->execute(new UpdateTicketRequest([
                'ticketID' => Input::get('ticket_id'),
                'requesterUserID' => $this->getUser()->id,
                'allocatedUserID' => 0
            ]));

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
