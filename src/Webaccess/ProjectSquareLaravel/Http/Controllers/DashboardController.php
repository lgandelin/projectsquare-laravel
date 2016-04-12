<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Calendar\GetEventsRequest;
use Webaccess\ProjectSquare\Requests\Notifications\GetUnreadNotificationsRequest;
use Webaccess\ProjectSquare\Requests\Notifications\ReadNotificationRequest;
use Webaccess\ProjectSquare\Requests\Tasks\GetTasksRequest;
use Webaccess\ProjectSquareLaravel\Decorators\NotificationDecorator;

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
            'tasks' => app()->make('GetTasksInteractor')->execute(new GetTasksRequest([
                    'userID' => $this->getUser()->id
            ])),
        ]);
    }

    public function refresh_notifications()
    {
        try {
            $response = app()->make('GetNotificationsInteractor')->getUnreadNotifications(new GetUnreadNotificationsRequest([
                'userID' =>$this->getUser()->id,
            ]));

            return response()->json([
                'notifications' => (new NotificationDecorator())->decorate($response->notifications)
            ], 200);
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
