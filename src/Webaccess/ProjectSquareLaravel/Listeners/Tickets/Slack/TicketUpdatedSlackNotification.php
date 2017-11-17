<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Slack;

use Webaccess\ProjectSquare\Events\Tickets\UpdateTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class TicketUpdatedSlackNotification
{
    public function handle(UpdateTicketEvent $event)
    {
        if (isset($event->ticketID) && $event->ticketID) {
            if ($ticket = Ticket::where('id', '=', $event->ticketID)->with('project', 'project.client', 'states', 'states.allocated_user', 'states.author_user', 'states.status')->first()) {
                $lines = [
                    '*Ticket :* `<' . route('tickets_edit', ['uuid' => $ticket->id]) . '|' . StringTool::getShortID($ticket->id) . '>` <' . route('tickets_edit', ['uuid' => $ticket->id]) . '|*' . $ticket->title . '*>',
                ];

                $modificationMade = false;

                if (isset($ticket->states[0]->status) && isset($ticket->states[1]->status) && $ticket->states[0]->status->id != $ticket->states[1]->status->id) {
                    $lines[] = '*Statut :* ~'.$ticket->states[1]->status->name.'~ '.$ticket->states[0]->status->name;
                    $modificationMade = true;
                }

                if (isset($ticket->states[0]->allocated_user) && isset($ticket->states[1]->allocated_user)) {
                    if ($ticket->states[0]->allocated_user->id != $ticket->states[1]->allocated_user->id) {
                        $lines[] = '*Utilisateur assigné :* ~'.$ticket->states[1]->allocated_user->complete_name.'~ '.$ticket->states[0]->allocated_user->complete_name;
                        $modificationMade = true;
                    }
                }

                if ($ticket->states[0]->due_date != $ticket->states[1]->due_date) {
                    $lines[] = '*Echéance :* ~'.($ticket->states[1]->due_date != '' ? $ticket->states[1]->due_date : 'N/A').'~ '.$ticket->states[0]->due_date;
                    $modificationMade = true;
                }


                if ($ticket->states[0]->priority != $ticket->states[1]->priority) {
                    $lines[] = '*Priorité :* ~'.($ticket->states[1]->priority != '' ? trans('projectsquare::generic.priority-'.$ticket->states[1]->priority) : 'N/A').'~ '.trans('projectsquare::generic.priority-'.$ticket->states[0]->priority);
                    $modificationMade = true;
                }

                if ($ticket->states[0]->comments) {
                    $lines[] = '*Commentaires :* '.$ticket->states[0]->comments;
                    $modificationMade = true;
                }

                $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $ticket->project->id);

                if ($modificationMade) {
                    SlackTool::send(
                        ((isset($ticket->project) && isset($ticket->project->client)) ? '<' . route('project_tickets', ['id' => $ticket->project->id]) . '|*[' . $ticket->project->client->name . '] ' . $ticket->project->name . '*>' : '') . ' *Un ticket a été modifié*',
                        implode("\n", $lines),
                        (isset($ticket->states[0]) && isset($ticket->states[0]->author_user)) ? $ticket->states[0]->author_user->complete_name : '',
                        null,
                        ($settingSlackChannel) ? $settingSlackChannel->value : '',
                        (isset($ticket->project) && $ticket->project->color != '') ? $ticket->project->color : '#36a64f'
                    );
                }
            }
        }
    }
}
