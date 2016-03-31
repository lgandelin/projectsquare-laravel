<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Calendar\CreateEventRequest;
use Webaccess\ProjectSquare\Requests\Calendar\DeleteEventRequest;
use Webaccess\ProjectSquare\Requests\Calendar\GetEventRequest;
use Webaccess\ProjectSquare\Requests\Calendar\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Calendar\UpdateEventRequest;

class CalendarController extends BaseController
{
    public function index()
    {
        $userID = (Input::get('filter_user')) ? Input::get('filter_user') : $this->getUser()->id;

        return view('projectsquare::calendar.index', [
            'projects' => app()->make('GetProjectsInteractor')->getProjects($this->getUser()->id),
            'events' => app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $userID,
                'projectID' => Input::get('filter_project'),
            ])),
            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList(
                $userID,
                env('TICKETS_PER_PAGE'),
                Input::get('filter_project')),
            'filters' => [
                'project' => Input::get('filter_project'),
                'user' => Input::get('filter_user'),
            ],
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'userID' => $userID,
            'currentUserID' => $this->getUser()->id,
        ]);
    }

    public function get_event()
    {
        try {
            $event = app()->make('GetEventInteractor')->execute(new GetEventRequest([
                'eventID' => Input::get('id'),
                'requesterUserID' => $this->getUser()->id,
            ]));
            $event->start_time = $event->startTime->format(DATE_ISO8601);
            $event->end_time = $event->endTime->format(DATE_ISO8601);
            $event->project_id = $event->projectID;

            return response()->json(['event' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        try {
            $response = app()->make('CreateEventInteractor')->execute(new CreateEventRequest([
                'name' => Input::get('name'),
                'userID' => Input::get('user_id') ? Input::get('user_id') : $this->getUser()->id,
                'startTime' => new \DateTime(Input::get('start_time')),
                'endTime' => new \DateTime(Input::get('end_time')),
                'projectID' => Input::get('project_id'),
                'ticketID' => Input::get('ticket_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $event = $response->event;
            $event->start_time = $event->startTime->format(DATE_ISO8601);
            $event->end_time = $event->endTime->format(DATE_ISO8601);
            if (isset($event->projectID)) {
                $project = app()->make('GetProjectInteractor')->getProject($event->projectID);
                if ($event->projectID == $project->id && isset($project->color)) {
                    $event->color = $project->color;
                }
            }

            return response()->json(['message' => trans('projectsquare::events.create_event_success'), 'event' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update()
    {
        try {
            $response = app()->make('UpdateEventInteractor')->execute(new UpdateEventRequest([
                'eventID' => Input::get('event_id'),
                'name' => Input::get('name'),
                'startTime' => new \DateTime(Input::get('start_time')),
                'endTime' => new \DateTime(Input::get('end_time')),
                'projectID' => Input::get('project_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $event = $response->event;
            $event->start_time = $event->startTime->format(DATE_ISO8601);
            $event->end_time = $event->endTime->format(DATE_ISO8601);
            if (isset($event->projectID)) {
                $project = app()->make('GetProjectInteractor')->getProject($event->projectID);
                if ($event->projectID == $project->id && isset($project->color)) {
                    $event->color = $project->color;
                }
            }

            return response()->json(['message' => trans('projectsquare::events.edit_event_success'), 'event' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete()
    {
        try {
            app()->make('DeleteEventInteractor')->execute(new DeleteEventRequest([
                'eventID' => Input::get('event_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            return response()->json(['message' => trans('projectsquare::events.delete_event_success')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
