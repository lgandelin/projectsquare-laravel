<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\DeleteTicketEvent;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;

class TicketDeletedEmailNotification
{
    public function handle(DeleteTicketEvent $event)
    {
        $ticket = (new EloquentTicketRepository())->getTicketWithStates($event->ticket->id);
        $project = (new EloquentProjectRepository())->getProjectModel($ticket->projectID);
        $author_user = app()->make('UserManager')->getUser($ticket->states[0]->authorUserID);

        if ($user = app()->make('UserManager')->getUser($ticket->states[0]->allocatedUserID)) {
            $email = $user->email;

            Mail::send('projectsquare::emails.ticket_deleted', array('ticket' => $ticket, 'project' => $project, 'user' => $user, 'author_user' => $author_user), function ($message) use ($email, $ticket) {
                $message->to($email)
                    ->from('no-reply@projectsquare.io')
                    ->subject('[projectsquare] Suppression du ticket : ' . $ticket->title);
            });
        }
    }
}
