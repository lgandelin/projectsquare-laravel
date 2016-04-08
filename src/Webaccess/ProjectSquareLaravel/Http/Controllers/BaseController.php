<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Webaccess\ProjectSquare\Requests\Calendar\GetEventRequest;
use Webaccess\ProjectSquare\Requests\Notifications\GetUnreadNotificationsRequest;
use Webaccess\ProjectSquareLaravel\Models\User;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentMessageRepository;

class BaseController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');

        if (Auth::user()) {
            view()->share('logged_in_user', $this->getUser());
        }

        view()->share('current_project', $this->getCurrentProject());
        view()->share('notifications', $this->getUnreadNotifications());
    }

    protected function getUser()
    {
        $user = Auth::user();

        return User::with('projects.client')->find($user->id);
    }

    protected function getUnreadNotifications()
    {
        if (Auth::user()) {
            $notifications = app()->make('GetNotificationsInteractor')->getUnreadNotifications(new GetUnreadNotificationsRequest([
                'userID' => Auth::user()->id,
            ]))->notifications;

            if (is_array($notifications) && sizeof($notifications) > 0) {
                foreach ($notifications as $notification) {
                    $this->prepareNotification($notification);
                }
            }

            return $notifications;
        }

        return 0;
    }

    protected function getCurrentProject()
    {
        return $this->request->session()->get('current_project');
    }

    protected function prepareNotification($notification)
    {
        $notification->time = $notification->time->format('d/m/Y H:i');
        if ($notification->type == 'EVENT_CREATED') {
            $event = app()->make('GetEventInteractor')->execute(new GetEventRequest([
                'eventID' => $notification->entityID
            ]));
            $notification->link = route('calendar');
            $notification->event_name = $event->name;
        } elseif ($notification->type == 'MESSAGE_CREATED') {
            $message = (new EloquentMessageRepository())->getMessage($notification->entityID);
            $user = app()->make('UserManager')->getUser($message->userID);
            $notification->link = route('conversation', ['id' => $message->conversationID]);
            $notification->author_name = $user->firstName . ' ' . $user->lastName;
        }
    }
}
