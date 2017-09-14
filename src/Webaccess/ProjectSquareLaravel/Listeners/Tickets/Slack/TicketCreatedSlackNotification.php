<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Slack;

use Webaccess\ProjectSquare\Events\Tickets\CreateTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class TicketCreatedSlackNotification
{
    public function handle(CreateTicketEvent $event)
    {
        if ($ticket = Ticket::where('id', '=', $event->ticketID)->with('project', 'type', 'states', 'states.allocated_user', 'states.author_user')->first()) {

            $lines = [
                '*Ticket :* `<' . route('tickets_edit', ['uuid' => $ticket->id]) . '|' . StringTool::getShortID($ticket->id) . '>` <' . route('tickets_edit', ['uuid' => $ticket->id]) . '|*' . $ticket->title . '*>',
                (isset($ticket->allocated_user) && $ticket->allocated_user->id) ? '*Utilisateur assigné :* ' . $ticket->allocated_user->complete_name : '',
                '*Description :* ' . $ticket->description,
            ];

            if (isset($ticket->states[0]->allocated_user) && $ticket->states[0]->allocated_user->id) {
                $lines[] = '*Utilisateur assigné :* ' . $ticket->states[0]->allocated_user->complete_name;
            }

            if ($ticket->type) {
                $lines[] = '*Type :* ' . $ticket->type->name;
            }

            if ($ticket->states[0]->priority) {
                $lines[] = '*Priorité :* ' . trans('projectsquare::generic.priority-' . $ticket->states[0]->priority);
            }

            if ($ticket->states[0]->due_date) {
                $lines[] = '*Echéance :* ' . $ticket->states[0]->due_date;
            }

            if ($ticket->states[0]->comments) {
                $lines[] = '*Commentaires :*  ' . $ticket->states[0]->comments;
            }

            $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $ticket->project->id);

            SlackTool::send(
                ((isset($ticket->project) && isset($ticket->project->client)) ? '<' . route('project_tickets', ['id' => $ticket->project->id]) . '|*['.$ticket->project->client->name.'] '.$ticket->project->name . '*>' : '') . ' *Un nouveau ticket a été créé*',
                implode("\n", $lines),
                (isset($ticket->states[0]->author_user)) ? $ticket->states[0]->author_user->complete_name : '',
                route('tickets_edit', ['uuid' => $ticket->id]),
                ($settingSlackChannel) ? $settingSlackChannel->value : '',
                (isset($ticket->project) && $ticket->project->color != '') ? $ticket->project->color : '#36a64f'
            );
        }
    }
}
