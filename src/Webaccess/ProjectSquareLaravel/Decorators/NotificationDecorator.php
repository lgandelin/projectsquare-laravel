<?php

namespace Webaccess\ProjectSquareLaravel\Decorators;

use Webaccess\ProjectSquare\Requests\Planning\GetEventRequest;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentFileRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentMessageRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTasksRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;

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
                    $notification->event_name = $event ? $event->name : '';
                    $notification->link = $event ? route('planning') : '';
                } elseif ($notification->type == 'MESSAGE_CREATED') {
                    $message = (new EloquentMessageRepository())->getMessage($notification->entityID);
                    $user = app()->make('UserManager')->getUser($message->userID);
                    $notification->link = $message ? route('conversations_view', ['id' => $message->conversationID]) : '';
                    $notification->author_name = $user ? $user->firstName.' '.$user->lastName : '';
                } elseif ($notification->type == 'TICKET_CREATED') {
                    $ticket = (new EloquentTicketRepository())->getTicket($notification->entityID);
                    $notification->ticket_title = $ticket->title;
                    $notification->link = route('tickets_edit', ['id' => $ticket->id]);
                } elseif ($notification->type == 'TASK_CREATED') {
                    $task = (new EloquentTasksRepository())->getTask($notification->entityID);
                    $notification->task_title = $task ? $task->title : '';
                    $notification->link = $task ? route('tasks_edit', ['id' => $task->id]) : '';
                } elseif ($notification->type == 'FILE_UPLOADED') {
                    $file = (new EloquentFileRepository())->getFile($notification->entityID);
                    $notification->file_name = $file ? $file->name : '';
                    $notification->link = $file ? route('project_files', ['id' => $file->project_id]) : '';
                }
            }
        }

        return $notifications;
    }
}
