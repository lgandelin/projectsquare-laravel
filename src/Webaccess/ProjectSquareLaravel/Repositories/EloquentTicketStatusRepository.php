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

    public static function createTicketStatus($name)
    {
        $ticketStatus = new TicketStatus();
        $ticketStatus->save();
        self::updateTicketStatus($ticketStatus->id, $name);
    }

    public static function updateTicketStatus($ticketStatusID, $name)
    {
        $ticketStatus = TicketStatus::find($ticketStatusID);
        $ticketStatus->name = $name;
        $ticketStatus->save();
    }

    public static function deleteTicketStatus($ticketStatusID)
    {
        $ticketStatus = self::getTicketStatus($ticketStatusID);
        $ticketStatus->delete();
    }
}
