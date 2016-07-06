<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Webaccess\ProjectSquare\Requests\Planning\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('projectsquare::dashboard.index', [
            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList($this->getUser()->id, env('TICKETS_PER_PAGE', 10)),
            'conversations' => $this->isUserAClient() ? app()->make('ConversationManager')->getConversationsByProject($this->getCurrentProject()->id, 5) : app()->make('ConversationManager')->getConversations($this->getUser()->id, 5),
            'events' => app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $this->getUser()->id,
            ])),
            'tasks' => app()->make('GetTasksInteractor')->execute(new GetTasksRequest([
                'userID' => $this->getUser()->id,
            ])),
        ]);
    }
}
