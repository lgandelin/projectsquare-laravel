<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\UpdateTicketEvent;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;
use Webaccess\ProjectSquareLaravel\Services\TicketStatusManager;

class TicketUpdatedEmailNotification
{
    public function handle(UpdateTicketEvent $event)
    {
        $ticket = (new EloquentTicketRepository())->getTicketWithStates($event->ticketID);
        $project = (new EloquentProjectRepository())->getProjectModel($ticket->projectID);
        $author_user = app()->make('UserManager')->getUser($ticket->states[0]->authorUserID);
        $newStatus = TicketStatusManager::getTicketStatus($ticket->states[0]->statusID);

        if ($user = app()->make('UserManager')->getUser($ticket->states[0]->allocatedUserID)) {
            $email = $user->email;

            Mail::send('projectsquare::emails.ticket_updated', array('ticket' => $ticket, 'project' => $project, 'user' => $user, 'author_user' => $author_user, 'new_status' => $newStatus), function ($message) use ($email, $ticket) {
                $message->to($email)
                    ->from('no-reply@projectsquare.io')
                    ->subject('[projectsquare] Modification du ticket : ' . $ticket->title);
            });
        }
    }
}
