<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketStatusRepository;

class TicketStatusManager
{
    public static function getTicketStatusesPaginatedList()
    {
        return EloquentTicketStatusRepository::getTicketStatusesPaginatedList(env('TICKET_STATUSES_PER_PAGE', 10));
    }

    public static function getTicketStatuses()
    {
        return EloquentTicketStatusRepository::getTicketStatuses();
    }

    public static function getTicketStatus($ticketStatusID)
    {
        if (!$ticketStatus = EloquentTicketStatusRepository::getTicketStatus($ticketStatusID)) {
            throw new \Exception(trans('projectsquare::ticket_statuses.ticket_status_not_found'));
        }

        return $ticketStatus;
    }

    public static function createTicketStatus($name, $include_in_planning = true)
    {
        EloquentTicketStatusRepository::createTicketStatus($name, $include_in_planning);
    }

    public static function updateTicketStatus($ticketStatusID, $name, $include_in_planning = true)
    {
        EloquentTicketStatusRepository::updateTicketStatus($ticketStatusID, $name, $include_in_planning);
    }

    public static function deleteTicketStatus($ticketStatusID)
    {
        EloquentTicketStatusRepository::deleteTicketStatus($ticketStatusID);
    }
}
