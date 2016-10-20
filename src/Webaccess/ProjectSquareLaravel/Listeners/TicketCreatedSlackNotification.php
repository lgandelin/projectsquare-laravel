<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Tickets\CreateTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class TicketCreatedSlackNotification
{
    public function handle(CreateTicketEvent $event)
    {
        if ($ticket = Ticket::find($event->ticketID)) {

            $lines = [
                (isset($ticket->project) && isset($ticket->project->client)) ? 'Projet : *['.$ticket->project->client->name.'] '.$ticket->project->name.'*' : '',
                (isset($ticket->last_state->status)) ? 'Etat du ticket : *' . $ticket->last_state->status->name . '*' : '',
                'Description : ' . $ticket->description,
            ];

            if (isset($ticket->last_state->allocated_user) && $ticket->last_state->allocated_user->id) {
                $lines[] = 'Utilisateur assigné : *' . $ticket->last_state->allocated_user->complete_name . '*';
            }

            if ($ticket->last_state->priority) {
                $lines[] = 'Priorité : *' . $ticket->last_state->priority . '*';
            }

            if ($ticket->last_state->dueDate) {
                $lines[] = 'Echéance : *' . $ticket->last_state->dueDate . '*';
            }

            if ($ticket->last_state->comments) {
                $lines[] = 'Commentaires : ' . $ticket->last_state->comments;
            }

            $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $ticket->projectID);

            SlackTool::send(
                'Nouveau ticket : ' . $ticket->title,
                implode("\n", $lines),
                (isset($ticket->last_state->author_user)) ? $ticket->last_state->author_user->complete_name : '',
                route('tickets_edit', ['id' => $ticket->id]),
                ($settingSlackChannel) ? $settingSlackChannel->value : '',
                '#36a64f'
            );
        }
    }
}
