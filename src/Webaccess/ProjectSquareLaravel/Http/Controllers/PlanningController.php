<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Decorators\EventDecorator;
use Webaccess\ProjectSquare\Exceptions\Events\EventUpdateNotAuthorizedException;
use Webaccess\ProjectSquare\Requests\Events\CreateEventRequest;
use Webaccess\ProjectSquare\Requests\Events\DeleteEventRequest;
use Webaccess\ProjectSquare\Requests\Events\GetEventRequest;
use Webaccess\ProjectSquare\Requests\Events\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Events\UpdateEventRequest;

class PlanningController extends BaseController
{
    public function index()
    {
        $userID = (Input::get('filter_user')) ? Input::get('filter_user') : $this->getUser()->id;

        $allocatedTickets = app()->make('GetTicketInteractor')->getTicketsList(
            $userID,
            Input::get('filter_project'),
            $userID
        );
        $nonAllocatedTickets = app()->make('GetTicketInteractor')->getTicketsList(
            $userID,
            Input::get('filter_project'),
            0
        );

        return view('projectsquare::planning.index', [
            'projects' => app()->make('GetProjectsInteractor')->getProjects($this->getUser()->id),
            'events' => app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $userID,
                'projectID' => Input::get('filter_project'),
            ])),
            'allocated_tickets' => $this->removeTicketsAlreadyScheduled($allocatedTickets),
            'non_allocated_tickets' => $this->removeTicketsAlreadyScheduled($nonAllocatedTickets),
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

            return response()->json([
                'event' => (new EventDecorator())->decorate($event),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
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

            return response()->json([
                'message' => trans('projectsquare::events.create_event_success'),
                'event' => (new EventDecorator())->decorate($response->event),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
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

            return response()->json([
                'message' => trans('projectsquare::events.edit_event_success'),
                'event' => (new EventDecorator())->decorate($response->event),
            ], 200);
        } catch (EventUpdateNotAuthorizedException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 301);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete()
    {
        try {
            app()->make('DeleteEventInteractor')->execute(new DeleteEventRequest([
                'eventID' => Input::get('event_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            return response()->json([
                'message' => trans('projectsquare::events.delete_event_success'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    protected function removeTicketsAlreadyScheduled($tickets)
    {
        foreach ($tickets as $i => $ticket) {
            $events = app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'ticketID' => $ticket->id
            ]));

            if (count($events) > 0) {
                unset($tickets[$i]);
            }
        }

        return $tickets;
    }
}
