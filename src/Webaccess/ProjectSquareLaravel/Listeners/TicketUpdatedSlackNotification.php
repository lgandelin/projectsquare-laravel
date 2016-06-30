<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Tickets\UpdateTicketEvent;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class TicketUpdatedSlackNotification
{
    public function handle(UpdateTicketEvent $event)
    {
        $ticket = (new EloquentTicketRepository())->getTicketWithStates($event->ticketID);

        $lines = [];
        $modificationMade = false;

        if (isset($ticket->states[0]->status) && isset($ticket->states[1]->status) && $ticket->states[0]->status->name != $ticket->states[1]->status->name) {
            $lines[] = 'Etat du ticket modifié : *'.$ticket->states[1]->status->name.'* => *'.$ticket->states[0]->status->name.'*';
            $modificationMade = true;
        }

        if (isset($ticket->states[0]->allocated_user) && isset($ticket->states[1]->allocated_user) && $ticket->states[0]->allocated_user->id != $ticket->states[1]->allocated_user->id) {
            $lines[] = 'Utilisateur assigné modifié : *'.$ticket->states[1]->allocated_user->complete_name.'* => *'.$ticket->states[0]->allocated_user->complete_name.'*';
            $modificationMade = true;
        }

        if ($ticket->states[0]->priority != $ticket->states[1]->priority) {
            $lines[] = 'Priorité modifiée : *'.$ticket->states[1]->priority.'* => *'.$ticket->states[0]->priority.'*';
            $modificationMade = true;
        }

        if ($ticket->states[0]->due_date != $ticket->states[1]->due_date) {
            $lines[] = 'Echéance : *'.$ticket->states[1]->due_date.'* => *'.$ticket->states[0]->due_date.'*';
            $modificationMade = true;
        }

        if ($ticket->states[0]->comments) {
            $lines[] = 'Commentaires : '.$ticket->states[0]->comments;
            $modificationMade = true;
        }

        $lines[] = route('tickets_edit', ['id' => $ticket->id]);

        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $ticket->project->id);

        if ($modificationMade) {
            SlackTool::send(
                'Modification du ticket : '.$ticket->title,
                implode("\n", $lines),
                (isset($ticket->states[0]) && isset($ticket->states[0]->author_user)) ? $ticket->states[0]->author_user->complete_name : '',
                route('tickets_edit', ['id' => $ticket->id]),
                ($settingSlackChannel) ? $settingSlackChannel->value : '',
                '#36a64f'
            );
        }
    }
}
