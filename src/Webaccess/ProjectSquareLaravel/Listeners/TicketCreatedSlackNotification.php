<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Tickets\CreateTicketEvent;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class TicketCreatedSlackNotification
{
    public function handle(CreateTicketEvent $event)
    {
        $ticket = (new EloquentTicketRepository())->getTicketWithStates($event->ticketID);

        $lines = [
            'Projet : *['.$ticket->project->client->name.'] '.$ticket->project->name.'*',
            (isset($ticket->states[0]->status)) ? 'Etat du ticket : *'.$ticket->states[0]->status->name.'*' : '',
            'Description : '.$ticket->description,
        ];

        if (isset($ticket->states[0]->allocated_user) && $ticket->states[0]->allocated_user->id) {
            $lines[] = 'Utilisateur assigné : *'.$ticket->states[0]->allocated_user->complete_name.'*';
        }

        if ($ticket->states[0]->priority) {
            $lines[] = 'Priorité : *'.$ticket->states[0]->priority.'*';
        }

        if ($ticket->states[0]->due_date) {
            $lines[] = 'Echéance : *'.$ticket->states[0]->due_date.'*';
        }

        if ($ticket->states[0]->comments) {
            $lines[] = 'Commentaires : '.$ticket->states[0]->comments;
        }

        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $ticket->project->id);

        SlackTool::send(
            'Nouveau ticket : '.$ticket->title,
            implode("\n", $lines),
            $ticket->states[0]->author_user->complete_name,
            route('tickets_edit', ['id' => $ticket->id]),
            ($settingSlackChannel) ? $settingSlackChannel->value : '',
            '#36a64f'
        );
    }
}
