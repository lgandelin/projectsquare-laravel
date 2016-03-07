<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Webaccess\ProjectSquare\Interactors\Tickets\GetTicketInteractor;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('projectsquare::dashboard.index', [
            'tickets' => (new GetTicketInteractor(new EloquentTicketRepository()))->getTicketsPaginatedList($this->getUser()->id, env('TICKETS_PER_PAGE', 10)),
            'conversations' => app()->make('ConversationManager')->getLastConversations(5),
            'alerts' => app()->make('AlertManager')->getLastAlerts(5),
        ]);
    }
}
