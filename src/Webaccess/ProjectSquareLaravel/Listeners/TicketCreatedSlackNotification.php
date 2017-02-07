<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Tickets\CreateTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class TicketCreatedSlackNotification
{
    public function handle(CreateTicketEvent $event)
    {
        if ($ticket = Ticket::find($event->ticketID)) {
            $project = (new EloquentProjectRepository())->getProjectModel($ticket->projectID);

            $lines = [
                (isset($ticket->project) && isset($ticket->project->client)) ? '*Projet :* ['.$ticket->project->client->name.'] '.$ticket->project->name : '',
                (isset($ticket->last_state->status)) ? '*Statut :* ' . $ticket->last_state->status->name : '',
                'Description : ' . $ticket->description,
            ];

            if (isset($ticket->last_state->allocated_user) && $ticket->last_state->allocated_user->id) {
                $lines[] = '*Utilisateur assigné :* ' . $ticket->last_state->allocated_user->complete_name;
            }

            if ($ticket->last_state->priority) {
                $lines[] = '*Priorité :* ' . trans('projectsquare::generic.priority-' . $ticket->last_state->priority);
            }

            if ($ticket->last_state->dueDate) {
                $lines[] = '*Echéance :* ' . $ticket->last_state->dueDate;
            }

            if ($ticket->last_state->comments) {
                $lines[] = '*Commentaires :*  ' . $ticket->last_state->comments;
            }

            $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $ticket->projectID);

            SlackTool::send(
                'Nouveau ticket : ' . $ticket->title,
                implode("\n", $lines),
                (isset($ticket->last_state->author_user)) ? $ticket->last_state->author_user->complete_name : '',
                route('tickets_edit', ['uuid' => $ticket->id]),
                ($settingSlackChannel) ? $settingSlackChannel->value : '',
                (isset($project) && $project->color != '') ? $project->color : '#36a64f'
            );
        }
    }
}
