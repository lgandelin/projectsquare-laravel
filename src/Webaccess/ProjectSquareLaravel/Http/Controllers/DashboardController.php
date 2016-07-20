<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Webaccess\ProjectSquare\Requests\Planning\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquare\Requests\Todos\GetTodosRequest;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('projectsquare::dashboard.index', [
            'tasks' => app()->make('GetTasksInteractor')->execute(new GetTasksRequest()),
            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList($this->getUser()->id, env('TICKETS_PER_PAGE', 10)),
            'conversations' => $this->isUserAClient() ? app()->make('ConversationManager')->getConversationsByProject($this->getCurrentProject()->id, 5) : app()->make('ConversationManager')->getConversations($this->getUser()->id, 5),
            'events' => app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $this->getUser()->id,
            ])),
            'todos' => app()->make('GetTodosInteractor')->execute(new GetTodosRequest([
                'userID' => $this->getUser()->id,
            ])),
        ]);
    }
}
