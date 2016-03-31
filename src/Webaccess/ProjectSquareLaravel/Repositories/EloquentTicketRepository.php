<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Ticket as TicketEntity;
use Webaccess\ProjectSquare\Entities\TicketState as TicketStateEntity;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Models\TicketState;
use Webaccess\ProjectSquareLaravel\Models\TicketStatus;
use Webaccess\ProjectSquareLaravel\Models\User;
use Webaccess\ProjectSquare\Repositories\TicketRepository;

class EloquentTicketRepository implements TicketRepository
{
    public $projectRepository;

    public function __construct()
    {
        $this->projectRepository = new EloquentProjectRepository();
    }

    public function getTicketsPaginatedList($userID, $limit, $projectID = null, $allocatedUserID = null, $statusID = null, $typeID = null)
    {
        $projectIDs = User::find($userID)->projects->pluck('id')->toArray();
        $tickets = Ticket::whereIn('project_id', $projectIDs)->with('type', 'last_state', 'states', 'states.author_user', 'states.status', 'last_state.author_user', 'last_state.allocated_user', 'last_state.status', 'project', 'project.client');

        if ($projectID) {
            $tickets->where('project_id', '=', $projectID);
        }

        if ($typeID) {
            $tickets->where('type_id', '=', $typeID);
        }

        if ($statusID) {
            $tickets->whereHas('last_state.status', function ($query) use ($statusID) {
                $query->where('id', '=', $statusID);
            });
        }

        if ($allocatedUserID) {
            $tickets->whereHas('last_state.allocated_user', function ($query) use ($allocatedUserID) {
                $query->where('id', '=', $allocatedUserID);
            });
        }

        return $tickets->orderBy('updated_at', 'DESC')->paginate($limit);
    }

    public function getTicket($ticketID, $userID = null)
    {
        if (!$ticketModel = Ticket::find($ticketID)) {
            throw new \Exception(trans('projectsquare::tickets.ticket_not_found'));
        }

        if ($userID != null && !$this->isUserAllowedToSeeTicket($userID, $ticketModel)) {
            throw new \Exception(trans('projectsquare::tickets.user_not_authorized_error'));
        }

        $ticket = new TicketEntity();
        $ticket->id = $ticketModel->id;
        $ticket->title = $ticketModel->title;
        $ticket->description = $ticketModel->description;
        $ticket->projectID = $ticketModel->project_id;
        $ticket->typeID = $ticketModel->type_id;
        $ticket->lastTypeID = $ticketModel->last_type_id;
        $ticket->createdAt = $ticketModel->updated_at;
        $ticket->updatedAt = $ticketModel->updated_at;

        return $ticket;
    }

    public function getTicketWithStates($ticketID)
    {
        return Ticket::with('states', 'states.author_user', 'states.allocated_user', 'states.status')->find($ticketID);
    }

    public function getTicketStatesPaginatedList($ticket, $limit)
    {
        return $ticket->states()->with('author_user', 'allocated_user', 'status')->paginate($limit);
    }

    public function updateTicket($ticketID, $statusID, $authorUserID, $allocatedUserID, $priority, $dueDate, $comments)
    {
        $this->addState($ticketID, $statusID, $authorUserID, $allocatedUserID, $priority, $dueDate, $comments);

        return $this->getTicket($ticketID);
    }

    public function addState($ticketID, $statusID, $authorUserID, $allocatedUserID, $priority, $dueDate, $comments)
    {
        $ticket = $this->getTicket($ticketID);

        $ticketState = new TicketState();
        $ticketState->ticket()->associate($ticket);

        $status = TicketStatus::find($statusID);
        $ticketState->status()->associate($status);

        if ($authorUser = User::find($authorUserID)) {
            $ticketState->author_user()->associate($authorUser);
        }

        if ($allocatedUser = User::find($allocatedUserID)) {
            $ticketState->allocated_user()->associate($allocatedUser);
        }

        $ticketState->priority = $priority;
        if ($dueDate) {
            $ticketState->due_date = $dueDate;
        }
        $ticketState->comments = $comments;
        $ticket->states()->save($ticketState);

        $ticket->last_state()->associate($ticketState);
        $ticket->save();
    }

    public function deleteTicket($ticketID)
    {
        if (!$ticketModel = Ticket::find($ticketID)) {
            throw new \Exception(trans('projectsquare::tickets.ticket_not_found'));
        }

        $ticketModel->delete();
    }

    public function isUserAllowedToSeeTicket($userID, $ticket)
    {
        $projectIDs = User::find($userID)->projects->pluck('id')->toArray();

        return in_array($ticket->project_id, $projectIDs);
    }

    public function persistTicket(TicketEntity $ticket)
    {
        $ticketModel = (!isset($ticket->id)) ? new Ticket() : Ticket::find($ticket->id);
        $ticketModel->title = $ticket->title;
        if ($project = $this->projectRepository->getProject($ticket->projectID)) {
            $ticketModel->project()->associate($project);
        }
        $ticketModel->description = $ticket->description;
        $ticketModel->type_id = $ticket->typeID;
        $ticketModel->save();

        $ticket->id = $ticketModel->id;

        return $ticket;
    }

    public function persistTicketState(TicketStateEntity $ticketState)
    {
        $ticketStateModel = new TicketState();
        $ticketStateModel->ticket_id = $ticketState->ticketID;
        $ticketStateModel->author_user_id = $ticketState->authorUserID;
        $ticketStateModel->allocated_user_id = $ticketState->allocatedUserID;
        $ticketStateModel->status_id = $ticketState->statusID;
        $ticketStateModel->priority = $ticketState->priority;
        if ($ticketState->dueDate) {
            $ticketStateModel->due_date = $ticketState->dueDate->format('Y-m-d');
        }
        $ticketStateModel->estimated_time = $ticketState->estimatedTime;
        $ticketStateModel->comments = $ticketState->comments;
        $ticketStateModel->save();
    }
}
