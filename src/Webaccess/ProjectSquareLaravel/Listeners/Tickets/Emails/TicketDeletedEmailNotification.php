<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\DeleteTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;

class TicketDeletedEmailNotification
{
    public function handle(DeleteTicketEvent $event)
    {
        if (isset($event->ticket->id) && $event->ticket->id) {
            if ($ticket = Ticket::where('id', '=', $event->ticket->id)->with('project', 'project.client', 'states', 'states.allocated_user', 'states.author_user')->first()) {

                if (isset($ticket->states[0]->allocated_user)) {
                    $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TICKET_DELETED', $ticket->states[0]->allocated_user->id);

                    if ($setting && boolval($setting->value) === true) {
                        $email = $ticket->states[0]->allocated_user->email;

                        Mail::send('projectsquare::emails.ticket_deleted', array('ticket' => $ticket), function ($message) use ($email, $ticket) {
                            $message->to($email)
                                ->from('no-reply@projectsquare.io')
                                ->subject('[projectsquare] Suppression du ticket : ' . $ticket->title);
                        });
                    }
                }
            }
        }
    }
}
