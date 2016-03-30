<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Webaccess\ProjectSquare\Requests\Calendar\GetEventsRequest;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('projectsquare::dashboard.index', [
            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList($this->getUser()->id, env('TICKETS_PER_PAGE', 10)),
            'conversations' => app()->make('ConversationManager')->getLastConversations($this->getUser()->id, 5),
            'alerts' => app()->make('AlertManager')->getLastAlerts(5),
            'events' => app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $this->getUser()->id
            ])),
        ]);
    }
}
