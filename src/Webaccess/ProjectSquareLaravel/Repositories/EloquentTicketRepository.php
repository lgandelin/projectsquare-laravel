<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Ramsey\Uuid\Uuid;
use Webaccess\ProjectSquare\Entities\Project as ProjectEntity;
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

    public function getTicketsPaginatedList($userID, $limit, $projectID = null, $allocatedUserID = null, $statusID = null, $typeID = null, $sortColumn = null, $sortOrder = null)
    {
        return $this->getTickets($userID, $projectID , $allocatedUserID, $statusID, $typeID, $sortColumn, $sortOrder)->paginate($limit);
    }

    public function getTicketsList($userID, $projectID = null, $allocatedUserID = null, $statusID = null, $typeID = null)
    {
        return $this->getTickets($userID, $projectID , $allocatedUserID, $statusID, $typeID)->get();
    }

    private function getTickets($userID, $projectID = null, $allocatedUserID = null, $statusID = null, $typeID = null, $sortColumn = null, $sortOrder = null)
    {
        //Ressource projects
        $projects = User::find($userID)->projects()->where('status_id', '=', ProjectEntity::IN_PROGRESS);
        $projectIDs = $projects->pluck('id')->toArray();

        if ($projectID) {
            $projectIDs = [$projectID];
        }

        $tickets = Ticket::whereIn('project_id', $projectIDs)->with('type', 'last_state', 'states', 'states.author_user', 'states.status', 'last_state.author_user', 'last_state.allocated_user', 'last_state.status', 'project', 'project.client');

        if ($typeID) {
            $tickets->where('type_id', '=', $typeID);
        }

        if ($statusID) {
            $tickets->whereHas('last_state.status', function ($query) use ($statusID) {
                $query->where('id', '=', $statusID);
            });
        } else {
            //Keep only wanted states
            $tickets->whereHas('last_state.status', function ($query) {
                $query->where('include_in_planning', '=', true);
            });
        }

        if ($allocatedUserID === 0) {
            $tickets->whereDoesntHave('last_state.allocated_user');
        } elseif ($allocatedUserID !== null) {
            $tickets->whereHas('last_state.allocated_user', function ($query) use ($allocatedUserID) {
                $query->where('id', '=', $allocatedUserID);
            });
        }

        if ($sortColumn == 'client') {
            $tickets->join('projects', 'projects.id', '=', 'tickets.project_id')
                    ->join('clients', 'clients.id', '=', 'projects.client_id')->orderBy('clients.name', $sortOrder ? $sortOrder : 'DESC');
        } elseif (in_array($sortColumn, ['priority', 'status_id', 'allocated_user_id'])) {
            $tickets->join('ticket_states', 'ticket_states.id', '=', 'tickets.last_state_id')->orderBy('ticket_states.'.$sortColumn, $sortOrder ? $sortOrder : 'DESC');
        } else {
            $tickets->orderBy($sortColumn ? $sortColumn : 'updated_at', $sortOrder ? $sortOrder : 'DESC');
        }

        return $tickets;
    }

    public function getTicket($ticketID, $userID = null)
    {
        if (!$ticketModel = Ticket::with('project')->with('last_state')->find($ticketID)) {
            throw new \Exception(trans('projectsquare::tickets.ticket_not_found'));
        }

        if ($userID != null && !$this->isUserAllowedToSeeTicket($userID, $ticketModel)) {
            throw new \Exception(trans('projectsquare::tickets.user_not_authorized_error'));
        }

        $ticket = new TicketEntity();
        $ticket->id = $ticketModel->id;
        $ticket->title = $ticketModel->title;
        $ticket->description = $ticketModel->description;
        $ticket->author_user = $ticketModel->author_user;
        $ticket->project = $ticketModel->project;
        $ticket->projectID = $ticketModel->project_id;
        $ticket->typeID = $ticketModel->type_id;
        $ticket->lastStateID = $ticketModel->last_state_id;
        $ticket->lastState = $ticketModel->last_state;
        $ticket->createdAt = $ticketModel->updated_at;
        $ticket->updatedAt = $ticketModel->updated_at;

        return $ticket;
    }

    public function getTicketWithStates($ticketID)
    {
        if ($ticketModel = Ticket::with('states', 'states.author_user', 'states.allocated_user', 'states.status')->find($ticketID)) {

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
                $ticketState->estimatedTimeDays = $state->estimated_time_days;
                $ticketState->estimatedTimeHours = $state->estimated_time_hours;
                $ticketState->spentTimeDays = $state->spent_time_days;
                $ticketState->spentTimeHours = $state->spent_time_hours;
                $ticketState->priority = $state->priority;
                $ticketState->ticketID = $state->ticket_id;
                $ticketState->createdAt = $state->updated_at;
                $ticketState->updatedAt = $state->updated_at;
                $ticket->states[] = $ticketState;
            }

            return $ticket;
        }

        return false;
    }

    public function getTicketStatesPaginatedList($ticketID, $limit)
    {
        $ticket = Ticket::with('states', 'states.author_user', 'states.allocated_user', 'states.status')->find($ticketID);

        return $ticket->states()->with('author_user', 'allocated_user', 'status')->paginate($limit);
    }

    public function getTicketsByProjectID($projectID)
    {
        return Ticket::where('project_id', '=', $projectID)->get();
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
        $project = $this->projectRepository->getProjectModel($ticket->projectID);

        $clientUsersID = [];
        if (isset($project->client_id)) {
            $clientUsersID = User::where('client_id', '=', $project->client_id)->pluck('id')->toArray();
        }

        return in_array($ticket->projectID, $projectIDs) || in_array($userID, $clientUsersID);
    }

    public function persistTicket(TicketEntity $ticket)
    {
        if (!isset($ticket->id)) {
            $ticketModel = new Ticket();
            $ticketID = Uuid::uuid4()->toString();
            $ticketModel->id = $ticketID;
            $ticket->id = $ticketID;
        } else {
            $ticketModel = Ticket::find($ticket->id);
        }
        $ticketModel->title = $ticket->title;
        if ($project = $this->projectRepository->getProjectModel($ticket->projectID)) {
            $ticketModel->project()->associate($project);
        }
        $ticketModel->description = $ticket->description;
        $ticketModel->type_id = $ticket->typeID;
        $ticketModel->save();

        return $ticket;
    }

    public function persistTicketState(TicketStateEntity $ticketState)
    {
        $ticketStateModel = new TicketState();
        $ticketStateModel->id = Uuid::uuid4()->toString();
        $ticketStateModel->ticket_id = $ticketState->ticketID;
        $ticketStateModel->author_user_id = $ticketState->authorUserID;
        $ticketStateModel->allocated_user_id = $ticketState->allocatedUserID;
        $ticketStateModel->status_id = $ticketState->statusID;
        $ticketStateModel->priority = $ticketState->priority;
        if ($ticketState->dueDate) {
            $ticketStateModel->due_date = $ticketState->dueDate->format('Y-m-d');
        }
        $ticketStateModel->estimated_time_days = $ticketState->estimatedTimeDays;
        $ticketStateModel->estimated_time_hours = $ticketState->estimatedTimeHours;
        $ticketStateModel->spent_time_days = $ticketState->spentTimeDays;
        $ticketStateModel->spent_time_hours = $ticketState->spentTimeHours;
        $ticketStateModel->comments = $ticketState->comments;
        $ticketStateModel->save();

        if ($ticket = Ticket::find($ticketState->ticketID)) {
            $ticket->last_state()->associate($ticketStateModel);
            $ticket->save();
        }
    }
}
