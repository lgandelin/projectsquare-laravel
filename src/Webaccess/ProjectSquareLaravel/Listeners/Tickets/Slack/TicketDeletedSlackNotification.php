<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Slack;

use Webaccess\ProjectSquare\Events\Tickets\DeleteTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class TicketDeletedSlackNotification
{
    public function handle(DeleteTicketEvent $event)
    {
        if (isset($event->ticket) && $event->ticket->id) {
            if ($ticket = Ticket::where('id', '=', $event->ticket->id)->with('project', 'project.client', 'states', 'states.allocated_user', 'states.author_user')->first()) {

                $lines = [
                    '*Ticket :* `<' . route('tickets_edit', ['uuid' => $ticket->id]) . '|' . StringTool::getShortID($ticket->id) . '>` <' . route('tickets_edit', ['uuid' => $ticket->id]) . '|*' . $ticket->title . '*>',
                    (isset($ticket->states[0]->allocated_user) && $ticket->states[0]->allocated_user->id) ? '*Utilisateur assigné :* ' . $ticket->states[0]->allocated_user->complete_name : '',
                ];

                $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $ticket->project->id);

                SlackTool::send(
                    ((isset($ticket->project) && isset($ticket->project->client)) ? '<' . route('project_index', ['id' => $ticket->project->id]) . '|*[' . $ticket->project->client->name . '] ' . $ticket->project->name . '*>' : '') . ' *Un ticket a été supprimé*',
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
