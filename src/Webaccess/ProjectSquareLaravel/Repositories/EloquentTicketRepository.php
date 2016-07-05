<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Ticket as TicketEntity;
use Webaccess\ProjectSquare\Entities\TicketState as TicketStateEntity;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\TicketState;
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
        $tickets = $this->getTickets($userID, $projectID , $allocatedUserID, $statusID, $typeID)->paginate($limit);

        if (!$statusID) {
            foreach ($tickets as $i => $ticket) {
                //Remove archived tickets
                if (isset($ticket->last_state->status) && $ticket->last_state->status && $ticket->last_state->status->id == env('ARCHIVED_TICKET_STATUS_ID')) {
                    unset($tickets[$i]);
                }
            }
        }

        return $tickets;
    }

    public function getTicketsList($userID, $projectID = null, $allocatedUserID = null, $statusID = null, $typeID = null)
    {
        return $this->getTickets($userID, $projectID , $allocatedUserID, $statusID, $typeID)->get();
    }

    private function getTickets($userID, $projectID = null, $allocatedUserID = null, $statusID = null, $typeID = null)
    {
        //Ressource projects
        $projectIDs = User::find($userID)->projects->pluck('id')->toArray();

        //Client project
        $user = User::find($userID);
        if (isset($user->client_id)) {
            $project = Project::where('client_id', '=', $user->client_id)->first();
            $projectIDs[]= $project->id;
        }

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

        if ($allocatedUserID > 0) {
            $tickets->whereHas('last_state.allocated_user', function ($query) use ($allocatedUserID) {
                $query->where('id', '=', $allocatedUserID);
            });
        } else if ($allocatedUserID === 0) {
            $tickets->has('last_state.allocated_user', '=', 0);
        }

        return $tickets->orderBy('updated_at', 'DESC');
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
        $ticket->lastStateID = $ticketModel->last_state_id;
        $ticket->createdAt = $ticketModel->updated_at;
        $ticket->updatedAt = $ticketModel->updated_at;

        return $ticket;
    }

    public function getTicketWithStates($ticketID)
    {
        $ticketModel = Ticket::with('states', 'states.author_user', 'states.allocated_user', 'states.status')->find($ticketID);

        $ticket = new TicketEntity();
        $ticket->id = $ticketModel->id;
        $ticket->title = $ticketModel->title;
        $ticket->description = $ticketModel->description;
        $ticket->projectID = $ticketModel->project_id;
        $ticket->typeID = $ticketModel->type_id;
        $ticket->lastStateID = $ticketModel->last_state_id;
        $ticket->createdAt = $ticketModel->updated_at;
        $ticket->updatedAt = $ticketModel->updated_at;
        $ticket->states = [];

        foreach ($ticketModel->states as $state) {
            $ticketState = new TicketStateEntity();
            $ticketState->id = $state->id;
            $ticketState->statusID = $state->status_id;
            $ticketState->allocatedUserID = $state->allocated_user_id;
            $ticketState->authorUserID = $state->author_user_id;
            $ticketState->comments = $state->comments;
            $ticketState->dueDate = $state->due_date;
            $ticketState->estimatedTime = $state->estimated_time;
            $ticketState->priority = $state->priority;
            $ticketState->ticketID = $state->ticket_id;
            $ticketState->createdAt = $state->updated_at;
            $ticketState->updatedAt = $state->updated_at;
            $ticket->states[]= $ticketState;
        }

        return $ticket;
    }

    public function getTicketStatesPaginatedList($ticketID, $limit)
    {
        $ticket = Ticket::with('states', 'states.author_user', 'states.allocated_user', 'states.status')->find($ticketID);

        return $ticket->states()->with('author_user', 'allocated_user', 'status')->paginate($limit);
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
        $project = $this->projectRepository->getProject($ticket->projectID);

        $clientUsersID = [];
        if (isset($project->client_id)) {
            $clientUsersID = User::where('client_id', '=', $project->client_id)->pluck('id')->toArray();
        }

        return in_array($ticket->projectID, $projectIDs) || in_array($userID, $clientUsersID);
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

        $ticket = Ticket::find($ticketState->ticketID);
        $ticket->last_state()->associate($ticketStateModel);
        $ticket->save();
    }
}
