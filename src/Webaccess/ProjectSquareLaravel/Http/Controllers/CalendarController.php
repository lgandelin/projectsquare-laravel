<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Interactors\Calendar\DeleteEventInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\GetUserEventsInteractor;
use Webaccess\ProjectSquare\Interactors\Calendar\UpdateEventInteractor;
use Webaccess\ProjectSquare\Requests\Calendar\DeleteEventRequest;
use Webaccess\ProjectSquare\Requests\Calendar\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Calendar\UpdateEventRequest;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentEventRepository;

class CalendarController extends BaseController
{
    public function index()
    {
        return view('projectsquare::calendar.index', [
            'events' => (new GetUserEventsInteractor(new EloquentEventRepository()))->execute(new GetEventsRequest([
                'userID' => $this->getUser()->id
            ]))
        ]);
    }

    public function update()
    {
        try {
            (new UpdateEventInteractor(
                new EloquentEventRepository()
            ))->execute(new UpdateEventRequest([
                'eventID' => Input::get('event_id'),
                'name' => Input::get('name'),
                'startTime' => new \DateTime(Input::get('start_time')),
                'endTime' => new \DateTime(Input::get('end_time')),
                'requesterUserID' => $this->getUser()->id,
            ]));

            return response()->json(['message' => trans('projectsquare::events.edit_event_success')], 200);
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
