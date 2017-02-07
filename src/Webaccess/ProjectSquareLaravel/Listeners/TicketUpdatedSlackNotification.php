<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Tickets\UpdateTicketEvent;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;
use Webaccess\ProjectSquareLaravel\Services\TicketStatusManager;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class TicketUpdatedSlackNotification
{
    public function handle(UpdateTicketEvent $event)
    {
        $ticket = (new EloquentTicketRepository())->getTicketWithStates($event->ticketID);
        $project = (new EloquentProjectRepository())->getProjectModel($ticket->projectID);

        $lines = [
            (isset($project) && isset($project->client)) ? '*Projet :* ['.$project->client->name.'] '.$project->name : '',
        ];
        $modificationMade = false;

        if (isset($ticket->states[0]->statusID)) {
            $newStatus = TicketStatusManager::getTicketStatus($ticket->states[0]->statusID);
            $oldStatus = TicketStatusManager::getTicketStatus($ticket->states[1]->statusID);

            if ($newStatus->id != $oldStatus->id) {
                $lines[] = '*Statut :* '.$oldStatus->name.' => '.$newStatus->name;
                $modificationMade = true;
            }
        }

        $newAllocatedUser = null;
        if (isset($ticket->states[0]->allocatedUserID)) {
            $newAllocatedUser = app()->make('UserManager')->getUser($ticket->states[0]->allocatedUserID);
        }

        $oldAllocatedUser = null;
        if (isset($ticket->states[1]->allocatedUserID)) {
            $oldAllocatedUser = app()->make('UserManager')->getUser($ticket->states[1]->allocatedUserID);
        }

        if ($newAllocatedUser != $oldAllocatedUser) {
            $lines[] = '*Utilisateur assigné :* '.$oldAllocatedUser->firstName . ' ' . $oldAllocatedUser->lastName .' => '.$newAllocatedUser->firstName . ' ' . $newAllocatedUser->lastName;
            $modificationMade = true;
        }

        if ($ticket->states[0]->priority != $ticket->states[1]->priority) {
            $lines[] = '*Priorité :* '.trans('projectsquare::generic.priority-'.$ticket->states[1]->priority).' => '.trans('projectsquare::generic.priority-'.$ticket->states[0]->priority);
            $modificationMade = true;
        }

        if ($ticket->states[0]->dueDate != $ticket->states[1]->dueDate) {
            $lines[] = '*Echéance :* '.($ticket->states[1]->dueDate != '' ? $ticket->states[1]->dueDate : 'N/A').' => '.($ticket->states[0]->dueDate != '' ? $ticket->states[0]->dueDate : 'N/A');
            $modificationMade = true;
        }

        if ($ticket->states[0]->comments) {
            $lines[] = '*Commentaires :* '.$ticket->states[0]->comments;
            $modificationMade = true;
        }

        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $ticket->projectID);

        if ($modificationMade) {

            SlackTool::send(
                'Modification du ticket : '.$ticket->title,
                implode("\n", $lines),
                'Louis Gandelin',
                route('tickets_edit', ['uuid' => $ticket->id]),
                ($settingSlackChannel) ? $settingSlackChannel->value : '',
                (isset($project) && $project->color != '') ? $project->color : '#36a64f'
            );
        }
    }
}
