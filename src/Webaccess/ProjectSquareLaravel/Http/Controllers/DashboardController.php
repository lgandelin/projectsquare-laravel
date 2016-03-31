<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Webaccess\ProjectSquare\Requests\Calendar\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Notifications\GetUnreadNotificationsRequest;

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

    public function refresh_notifications()
    {
        try {
            $notifications = [];
            if (Auth::user()) {
                $notifications = app()->make('GetNotificationsInteractor')->getUnreadNotifications(new GetUnreadNotificationsRequest([
                    'userID' => Auth::user()->id,
                ]))->notifications;
            }

            return response()->json(['notifications' => $notifications], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
