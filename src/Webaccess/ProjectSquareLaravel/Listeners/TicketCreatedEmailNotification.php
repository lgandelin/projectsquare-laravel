<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\CreateTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;

class TicketCreatedEmailNotification
{
    public function handle(CreateTicketEvent $event)
    {
        if ($ticket = Ticket::find($event->ticketID)) {

            $user = app()->make('UserManager')->getUser($ticket->states[0]->allocated_user_id);
            $authorUser = app()->make('UserManager')->getUser($ticket->states[0]->author_user_id);

            if ($user->id != $authorUser->id) {
                $email = $user->email;

                Mail::send('projectsquare::emails.ticket_created', array('ticket' => $ticket, 'user' => $user), function ($message) use ($email, $ticket) {
                    $message->to($email)
                        ->from('no-reply@projectsquare.fr')
                        ->subject('[projectsquare] Un nouveau ticket vous a été assigné');
                });
            }
        }
    }
}
