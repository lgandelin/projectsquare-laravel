<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquareLaravel\Models\TicketStatus;
use Webaccess\ProjectSquare\Repositories\TicketStatusRepository;

class EloquentTicketStatusRepository implements TicketStatusRepository
{
    public static function getTicketStatus($ticketStatusID)
    {
        return TicketStatus::find($ticketStatusID);
    }

    public static function getTicketStatuses()
    {
        return TicketStatus::all();
    }

    public static function getTicketStatusesPaginatedList($limit)
    {
        return TicketStatus::paginate($limit);
    }

    public static function createTicketStatus($name, $include_in_planning)
    {
        $ticketStatus = new TicketStatus();
        $ticketStatus->save();
        self::updateTicketStatus($ticketStatus->id, $name, $include_in_planning);
    }

    public static function updateTicketStatus($ticketStatusID, $name, $include_in_planning)
    {
        $ticketStatus = TicketStatus::find($ticketStatusID);
        $ticketStatus->name = $name;
        $ticketStatus->include_in_planning = $include_in_planning;
        $ticketStatus->save();
    }

    public static function deleteTicketStatus($ticketStatusID)
    {
        $ticketStatus = self::getTicketStatus($ticketStatusID);
        $ticketStatus->delete();
    }
}
