<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Interactors\Calendar\CreateEventInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\DeleteEventInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\GetEventInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\GetEventsInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\UpdateEventInteractor;
use Webaccess\ProjectSquare\Interactors\Projects\GetProjectInteractor;
use Webaccess\ProjectSquare\Interactors\Projects\GetProjectsInteractor;
use Webaccess\ProjectSquare\Interactors\Tickets\GetTicketInteractor;
use Webaccess\ProjectSquare\Requests\Calendar\CreateEventRequest;
use Webaccess\ProjectSquare\Requests\Calendar\DeleteEventRequest;
use Webaccess\ProjectSquare\Requests\Calendar\GetEventRequest;
use Webaccess\ProjectSquare\Requests\Calendar\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Calendar\UpdateEventRequest;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentEventRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;

class CalendarController extends BaseController
{
    public function index()
    {
        $userID = (Input::get('filter_user')) ? Input::get('filter_user') : $this->getUser()->id;

        return view('projectsquare::calendar.index', [
            'projects' => (new GetProjectsInteractor(new EloquentProjectRepository()))->getProjects($this->getUser()->id),
            'events' => (new GetEventsInteractor(new EloquentEventRepository()))->execute(new GetEventsRequest([
                'userID' => $userID,
                'projectID' => Input::get('filter_project'),
            ])),
            'tickets' => (new GetTicketInteractor(new EloquentTicketRepository()))->getTicketsPaginatedList(
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
            $event = (new GetEventInteractor(new EloquentEventRepository()))->execute(new GetEventRequest([
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
            $response = (new CreateEventInteractor(
                new EloquentEventRepository()
            ))->execute(new CreateEventRequest([
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
                $project = (new GetProjectInteractor(new EloquentProjectRepository()))->getProject($event->projectID);
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
            $response = (new UpdateEventInteractor(
                new EloquentEventRepository()
            ))->execute(new UpdateEventRequest([
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
                $project = (new GetProjectInteractor(new EloquentProjectRepository()))->getProject($event->projectID);
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
            (new DeleteEventInteractor(
                new EloquentEventRepository()
            ))->execute(new DeleteEventRequest([
                'eventID' => Input::get('event_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            return response()->json(['message' => trans('projectsquare::events.delete_event_success')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
