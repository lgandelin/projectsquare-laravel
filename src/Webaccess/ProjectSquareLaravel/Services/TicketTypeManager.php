<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketTypeRepository;

class TicketTypeManager
{
    public static function getTicketTypesPaginatedList()
    {
        return EloquentTicketTypeRepository::getTicketTypesPaginatedList(env('TICKET_TYPES_PER_PAGE', 10));
    }

    public static function getTicketTypes()
    {
        return EloquentTicketTypeRepository::getTicketTypes();
    }

    public static function getTicketType($ticketTypeID)
    {
        if (!$ticketType = EloquentTicketTypeRepository::getTicketType($ticketTypeID)) {
            throw new \Exception(trans('projectsquare::ticket_types.ticket_type_not_found'));
        }

        return $ticketType;
    }

    public static function createTicketType($name)
    {
        EloquentTicketTypeRepository::createTicketType($name);
    }

    public static function updateTicketType($ticketTypeID, $name)
    {
        EloquentTicketTypeRepository::updateTicketType($ticketTypeID, $name);
    }

    public static function deleteTicketType($ticketTypeID)
    {
        EloquentTicketTypeRepository::deleteTicketType($ticketTypeID);
    }
}
