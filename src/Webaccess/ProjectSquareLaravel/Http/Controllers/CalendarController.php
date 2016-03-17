<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Webaccess\ProjectSquare\Interactors\Calendar\GetUserEventsInteractor;
use Webaccess\ProjectSquare\Requests\Calendar\GetEventsRequest;
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
}
