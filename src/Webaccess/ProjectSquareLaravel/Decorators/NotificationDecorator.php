<?php

namespace Webaccess\ProjectSquareLaravel\Decorators;

use Webaccess\ProjectSquare\Requests\Planning\GetEventRequest;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentMessageRepository;

class NotificationDecorator
{
    public function decorate($notifications)
    {
        if (is_array($notifications) && sizeof($notifications) > 0) {
            foreach ($notifications as $notification) {
                $notification->time = $notification->time->format('d/m/Y H:i');
                if ($notification->type == 'EVENT_CREATED') {
                    $event = app()->make('GetEventInteractor')->execute(new GetEventRequest([
                        'eventID' => $notification->entityID,
                    ]));
                    $notification->link = route('planning');
                    $notification->event_name = $event->name;
                } elseif ($notification->type == 'MESSAGE_CREATED') {
                    $message = (new EloquentMessageRepository())->getMessage($notification->entityID);
                    $user = app()->make('UserManager')->getUser($message->userID);
                    $notification->link = route('conversation', ['id' => $message->conversationID]);
                    $notification->author_name = $user->firstName.' '.$user->lastName;
                }
            }
        }

        return $notifications;
    }
}
