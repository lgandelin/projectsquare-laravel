<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquareLaravel\Models\TicketType;
use Webaccess\ProjectSquare\Repositories\TicketTypeRepository;

class EloquentTicketTypeRepository implements TicketTypeRepository
{
    public static function getTicketType($ticketTypeID)
    {
        return TicketType::find($ticketTypeID);
    }

    public static function getTicketTypes()
    {
        return TicketType::all();
    }

    public static function getTicketTypesPaginatedList($limit)
    {
        return TicketType::paginate($limit);
    }

    public static function createTicketType($name)
    {
        $ticketType = new TicketType();
        $ticketType->save();
        self::updateTicketType($ticketType->id, $name);
    }

    public static function updateTicketType($ticketTypeID, $name)
    {
        $ticketType = TicketType::find($ticketTypeID);
        $ticketType->name = $name;
        $ticketType->save();
    }

    public static function deleteTicketType($ticketTypeID)
    {
        $ticketType = self::getTicketType($ticketTypeID);
        $ticketType->delete();
    }
}
