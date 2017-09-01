<?php

namespace Webaccess\ProjectSquareLaravel\Decorators;

use DateTime;
use Webaccess\ProjectSquare\Requests\Planning\GetEventRequest;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentFileRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentMessageRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTasksRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;

class NotificationDecorator
{
    public function decorate($notifications)
    {
        if (is_array($notifications) && sizeof($notifications) > 0) {
            foreach ($notifications as $i => $notification) {
                $notification->relative_date = self::getRelativeDate($notification->createdAt);
                /*if ($notification->type == 'EVENT_CREATED') {
                    try {
                        $event = app()->make('GetEventInteractor')->execute(new GetEventRequest([
                            'eventID' => $notification->entityID,
                        ]));
                        $notification->event_name = $event ? $event->name : '';
                        $notification->link = $event ? route('planning') : '';
                    } catch (\Exception $e) {
                        unset($notifications[$i]);
                    }
                }*/
                if ($notification->type == 'MESSAGE_CREATED') {
                    try {
                        $message = (new EloquentMessageRepository())->getMessage($notification->entityID);
                        $user = app()->make('UserManager')->getUser($message->userID);
                        $notification->message = $message;
                        $notification->link = $message ? route('conversations_view', ['id' => $message->conversationID]) : '';
                        $notification->message->user = $user;
                    } catch (\Exception $e) {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'TICKET_CREATED' || $notification->type == 'TICKET_UPDATED') {
                    try {
                        $ticket = (new EloquentTicketRepository())->getTicket($notification->entityID);
                        $notification->ticket = $ticket;
                        $notification->ticket_title = $ticket->title;
                        $notification->link = route('tickets_edit', ['id' => $ticket->id]);
                    } catch (\Exception $e) {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'TASK_CREATED' || $notification->type == 'TASK_UPDATED') {
                    try {
                        $task = (new EloquentTasksRepository())->getTask($notification->entityID);
                        $notification->task = $task;
                        $notification->task_title = $task ? $task->title : '';
                        $notification->link = $task ? route('tasks_edit', ['id' => $task->id]) : '';
                    } catch (\Exception $e) {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'FILE_UPLOADED') {
                    try {
                        $file = (new EloquentFileRepository())->getFile($notification->entityID);
                        $notification->file = $file;
                        $notification->file_name = $file ? $file->name : '';
                        $notification->link = $file ? route('project_files', ['id' => $file->project_id]) : '';
                    } catch(\Exception $e) {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'ASSIGNED_TO_PROJECT') {
                    try {
                        $project = (new EloquentProjectRepository())->getProject($notification->entityID);
                        $notification->project = $project;
                        $notification->link = $project ? route('project_tasks', ['id' => $project->id]) : '';
                    } catch(\Exception $e) {
                        unset($notifications[$i]);
                    }
                }
            }
        }

        return $notifications;
    }

    public static function getRelativeDate($date) {
        $timestamp = $date->getTimestamp();
        $seconds = time() - $timestamp;
        if ($seconds == 0) {
            return 'à l\'instant';
        } elseif ($seconds > 0) {
            $day_diff = floor($seconds / 86400);
            if($day_diff == 0) {
                if($seconds < 60) return 'à l\'instant';
                if($seconds < 120) return 'il y a une minute';
                if($seconds < 3600) return 'il y a ' . floor($seconds / 60) . ' minutes';
                if($seconds < 7200) return 'il y a une heure';
                if($seconds < 86400) return 'il y a ' . floor($seconds / 3600) . ' heures';
            }

            if($day_diff == 1) { return 'hier'; }
            if($day_diff < 7) { return 'il y a ' .$day_diff . ' jours'; }
            if($day_diff < 31) { return 'il y a ' . ceil($day_diff / 7) . ' semaines'; }
            if($day_diff < 60) { return 'le mois dernier'; }

            return strftime('%B %Y', $timestamp);
        }

        return '';
    }
}