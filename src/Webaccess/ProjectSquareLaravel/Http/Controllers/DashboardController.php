<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Calendar\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Notifications\GetUnreadNotificationsRequest;
use Webaccess\ProjectSquare\Requests\Notifications\ReadNotificationRequest;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('projectsquare::dashboard.index', [
            'tickets' => app()->make('GetTicketInteractor')->getTicketsPaginatedList($this->getUser()->id, env('TICKETS_PER_PAGE', 10)),
            'conversations' => app()->make('ConversationManager')->getLastConversations($this->getUser()->id, 5),
            'alerts' => app()->make('AlertManager')->getLastAlerts(5),
            'events' => app()->make('GetEventsInteractor')->execute(new GetEventsRequest([
                'userID' => $this->getUser()->id,
            ])),
        ]);
    }

    public function refresh_notifications()
    {
        try {
            $notifications = [];
            if (Auth::user()) {
                $notifications = app()->make('GetNotificationsInteractor')->getUnreadNotifications(new GetUnreadNotificationsRequest([
                    'userID' =>$this->getUser()->id,
                ]))->notifications;

                if (is_array($notifications) && sizeof($notifications) > 0) {
                    foreach ($notifications as $notification) {
                        $notification->time = $notification->time->format('d/m/Y H:i');
                    }
                }
            }

            return response()->json(['notifications' => $notifications], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function read_notification()
    {
        try {
            $response = app()->make('ReadNotificationInteractor')->execute(new ReadNotificationRequest([
                'notificationID' => Input::get('id'),
                'userID' => $this->getUser()->id,
            ]));

            return response()->json(['notification' => $response->notification], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
