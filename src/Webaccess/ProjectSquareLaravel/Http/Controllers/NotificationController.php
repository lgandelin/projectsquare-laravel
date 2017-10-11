<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Notifications\GetUnreadNotificationsRequest;
use Webaccess\ProjectSquare\Requests\Notifications\ReadNotificationRequest;

class NotificationController extends BaseController
{
    public function read()
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

    public function get_notifications()
    {
        try {
            $response = app()->make('GetNotificationsInteractor')->getUnreadNotifications(new GetUnreadNotificationsRequest([
                'userID' => $this->getUser()->id,
            ]));

            return response()->json([
                'notifications' => $response->notifications,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
