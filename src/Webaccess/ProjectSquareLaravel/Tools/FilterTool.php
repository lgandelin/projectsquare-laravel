<?php

namespace Webaccess\ProjectSquareLaravel\Tools;

use Webaccess\ProjectSquare\Entities\Task;

class FilterTool
{
    public static function filterTicketList($tickets)
    {
        foreach ($tickets as $i => $ticket) {

            //Remove archived tickets
            if (isset($ticket->last_state->status) && !$ticket->last_state->status->include_in_planning) {
                unset($tickets[$i]);
            }
        }

        return $tickets;
    }

    public static function filterTaskList($tasks)
    {
        foreach ($tasks as $i => $task) {

            //Remove completed tasks
            if ($task->status_id == Task::COMPLETED)
                unset($tasks[$i]);
        }

        return $tasks;
    }
}