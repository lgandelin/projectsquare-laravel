<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Webaccess\ProjectSquare\Requests\Planning\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquare\Requests\Todos\GetTodosRequest;
use Webaccess\ProjectSquare\Requests\Calendar\GetStepsRequest;

class DashboardController extends BaseController
{
    public function index()
    {
        $this->initWidgetsIfNecessary();
        return view('projectsquare::dashboard.index', [
            'widgets' => json_decode($_COOKIE['dashboard-widgets']),
            'tasks' => app()->make('GetTasksInteractor')->getTasksPaginatedList(env('TASKS_PER_PAGE', 10), new GetTasksRequest()),
            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList($this->getUser()->id, env('TICKETS_PER_PAGE', 10)),
            'conversations' => $this->isUserAClient() ? app()->make('ConversationManager')->getConversationsByProject($this->getCurrentProject()->id, 5) : app()->make('ConversationManager')->getConversations($this->getUser()->id, 5),
            'events' => app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $this->getUser()->id,
            ])),
            'todos' => app()->make('GetTodosInteractor')->execute(new GetTodosRequest([
                'userID' => $this->getUser()->id,
            ])),

            'steps' => ($this->getCurrentProject()) ? app()->make('GetStepsInteractor')->execute(new GetStepsRequest([
                'projectID' => $this->getCurrentProject()->id,
            ])) : [],
        ]);
    }

    private function initWidgetsIfNecessary()
    {
        $widgets[]= ['name' => 'tickets', 'width' => 7];
        $widgets[]= ['name' => 'messages', 'width' => 5];
        
        if (!$this->isUserAClient()) {
            $widgets[]= ['name' => 'tasks', 'width' => 12];
            $widgets[]= ['name' => 'planning', 'width' => 12];
        } else {
            $widgets[]= ['name' => 'calendar', 'width' => 6];
        }

        if (!isset($_COOKIE['dashboard-widgets'])) {
            $_COOKIE['dashboard-widgets'] = json_encode($widgets);
        }
    }
}
