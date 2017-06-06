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
            foreach ($notifications as $i => $notification) {
                $notification->time = $notification->time->format('d/m/Y H:i');
                if ($notification->type == 'EVENT_CREATED') {
                    if ($event = app()->make('GetEventInteractor')->execute(new GetEventRequest([
                        'eventID' => $notification->entityID,
                    ]))) {
                        $notification->event_name = $event ? $event->name : '';
                        $notification->link = $event ? route('planning') : '';
                    } else {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'MESSAGE_CREATED') {
                    if ($message = (new EloquentMessageRepository())->getMessage($notification->entityID)) {
                        $user = app()->make('UserManager')->getUser($message->userID);
                        $notification->link = $message ? route('conversations_view', ['id' => $message->conversationID]) : '';
                        $notification->author_name = $user ? $user->firstName.' '.$user->lastName : '';
                    } else {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'TICKET_CREATED' || $notification->type == 'TICKET_UPDATED') {
                    try {
                        $ticket = (new EloquentTicketRepository())->getTicket($notification->entityID);
                        $notification->ticket_title = $ticket->title;
                        $notification->link = route('tickets_edit', ['id' => $ticket->id]);
                    } catch (\Exception $e) {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'TASK_CREATED' || $notification->type == 'TASK_UPDATED') {
                    if ($task = (new EloquentTasksRepository())->getTask($notification->entityID)) {
                        $notification->task_title = $task ? $task->title : '';
                        $notification->link = $task ? route('tasks_edit', ['id' => $task->id]) : '';
                    } else {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'FILE_UPLOADED') {
                    if ($file = (new EloquentFileRepository())->getFile($notification->entityID)) {
                        $notification->file_name = $file ? $file->name : '';
                        $notification->link = $file ? route('project_files', ['id' => $file->project_id]) : '';
                    } else {
                        unset($notifications[$i]);
                    }
                }
            }
        }

        return $notifications;
    }
}