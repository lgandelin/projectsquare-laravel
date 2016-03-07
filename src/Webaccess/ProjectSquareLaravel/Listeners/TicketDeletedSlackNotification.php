<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Tickets\DeleteTicketEvent;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;
use Webaccess\ProjectSquareLaravel\Tools\SlackTool;

class TicketDeletedSlackNotification
{
    public function handle(DeleteTicketEvent $event)
    {
        $ticket = (new EloquentTicketRepository())->getTicketWithStates($event->ticket->id);
        $lines = [];

        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $ticket->project->id);

        SlackTool::send(
            'Ticket supprimé : '.$ticket->title,
            implode("\n", $lines),
            (isset($ticket->states[0])) ? $ticket->states[0]->author_user->complete_name : '',
            null,
            ($settingSlackChannel) ? $settingSlackChannel->value : '',
            '#36a64f'
        );
    }
}
