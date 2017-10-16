<?php

namespace Webaccess\ProjectSquareLaravel\Decorators;

use DateTime;
use Webaccess\ProjectSquare\Requests\Planning\GetEventRequest;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentEventRepository;
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
                if ($notification->type == 'EVENT_CREATED') {
                    try {
                        $event = (new EloquentEventRepository())->getEvent($notification->entityID);
                        $notification->event = $event;
                        $notification->link = route('planning');
                        $notification->title = 'Nouvel évenement planning';
                        $notification->authorCompleteName = 'L\'équipe Projectsquare';
                        $notification->description = sprintf("L'évenement <strong>%s</strong> a été créé dans votre planning du %s au %s", $notification->event->name, $notification->event->startTime->format('d/m/y H\hi'), $notification->event->endTime->format('d/m/y H\hi'));
                        $notification->projectName = $event->projectName ? $event->projectName : 'Divers';
                        $notification->projectColor = $event->color ? $event->color : '#0a6a86';
                    } catch (\Exception $e) {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'TICKET_CREATED' || $notification->type == 'TICKET_UPDATED') {
                    try {
                        $ticket = (new EloquentTicketRepository())->getTicket($notification->entityID);
                        $notification->ticket = $ticket;
                        $notification->title = $ticket ? $ticket->title : '';
                        $notification->link = route('tickets_edit', ['id' => $ticket->id]);
                        $notification->authorCompleteName = $ticket->lastState->author_user->complete_name;
                        $notification->authorAvatar = (file_exists('uploads/users/' . $ticket->lastState->author_user->id . '/avatar.jpg')) ? asset('uploads/users/' . $ticket->lastState->author_user->id . '/avatar.jpg') : asset('img/default-avatar.jpg');
                        $notification->hasAvatar = true;
                        $notification->description = sprintf("Statut : <strong>%s</strong>", $notification->ticket->lastState->status->name);
                        $notification->projectName = $ticket->project->name;
                        $notification->projectColor = $ticket->project->color;
                    } catch (\Exception $e) {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'TASK_CREATED' || $notification->type == 'TASK_UPDATED') {
                    try {
                        $task = (new EloquentTasksRepository())->getTask($notification->entityID);
                        $notification->task = $task;
                        $notification->title = $task ? $task->title : '';
                        $notification->link = $task ? route('tasks_edit', ['id' => $task->id]) : '';
                        $notification->authorCompleteName = $task->author_user->complete_name;
                        $notification->authorAvatar = (file_exists('uploads/users/' . $task->author_user->id . '/avatar.jpg')) ? asset('uploads/users/' . $task->author_user->id . '/avatar.jpg') : asset('img/default-avatar.jpg');
                        $notification->hasAvatar = true;
                        $statusLabel = "";
                        if ($notification->task->statusID == 1) $statusLabel = "A faire";
                        elseif ($notification->task->statusID == 2) $statusLabel = "En cours";
                        elseif ($notification->task->statusID == 3) $statusLabel = "Terminé";
                        $notification->description = sprintf("Statut : <strong>%s</strong>", $statusLabel);
                        $notification->projectName = $task->project->name;
                        $notification->projectColor = $task->project->color;
                    } catch (\Exception $e) {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'FILE_UPLOADED') {
                    try {
                        $file = (new EloquentFileRepository())->getFile($notification->entityID);
                        $notification->file = $file;
                        $notification->file_name = $file ? $file->name : '';
                        $notification->title = 'Nouveau fichier uploadé';
                        $notification->link = $file ? route('project_files', ['id' => $file->project_id]) : '';
                        $notification->authorCompleteName = 'L\'équipe Projectsquare';
                        $notification->description = sprintf("Fichier : <strong>%s</strong>", $notification->file_name);
                        $notification->projectName = $file->project->name;
                        $notification->projectColor = $file->project->color;
                    } catch(\Exception $e) {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'ASSIGNED_TO_PROJECT') {
                    try {
                        if ($project = (new EloquentProjectRepository())->getProject($notification->entityID)) {
                            $notification->project = $project;
                            $notification->title = 'Nouvelle affectation projet';
                            $notification->link = $project ? route('project_tasks', ['id' => $project->id]) : '';
                            $notification->authorCompleteName = 'L\'équipe Projectsquare';
                            $notification->description = sprintf("Vous avez été affecté au projet <strong>%s</strong>.", $notification->project->name);
                            $notification->projectName = $project->name;
                            $notification->projectColor = $project->color;
                        } else {
                            unset($notifications[$i]);
                        }
                    } catch(\Exception $e) {
                        unset($notifications[$i]);
                    }
                } elseif ($notification->type == 'MESSAGE_CREATED') {
                    try {
                        $message = (new EloquentMessageRepository())->getMessage($notification->entityID);
                        $user = app()->make('UserManager')->getUser($message->userID);
                        $notification->message = $message;
                        $notification->title = ($user) ? $user->firstName . ' ' . $user->lastName : 'Nouveau message';
                        $notification->link = $message ? route('conversations_view', ['id' => $message->conversationID]) : '';
                        $notification->authorCompleteName = ($user) ? $user->firstName . ' ' . $user->lastName : '';
                        $notification->authorAvatar = (file_exists('uploads/users/' . $user->id . '/avatar.jpg')) ? asset('uploads/users/' . $user->id . '/avatar.jpg') : asset('img/default-avatar.jpg');
                        $notification->hasAvatar = true;
                        $notification->description = $message->content;
                        $notification->projectName = $message->project->name;
                        $notification->projectColor = $message->project->color;
                    } catch (\Exception $e) {
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
                if($seconds <= 3600) return 'il y a ' . floor($seconds / 60) . ' minutes';
                if($seconds < 7200) return 'il y a une heure';
                if($seconds <= 86400) return 'il y a ' . floor($seconds / 3600) . ' heures';
            }

            if($day_diff == 1) { return 'hier'; }
            if($day_diff <= 7) { return 'il y a ' .$day_diff . ' jours'; }
            if($day_diff < 31) { return 'il y a ' . ceil($day_diff / 7) . ' semaines'; }
            if($day_diff < 60) { return 'le mois dernier'; }

            return strftime('%B %Y', $timestamp);
        }

        return '';
    }
}