<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\UpdateTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;

class TicketUpdatedEmailNotification
{
    public function handle(UpdateTicketEvent $event)
    {
        if (isset($event->ticketID) && $event->ticketID) {
            if ($ticket = Ticket::where('id', '=', $event->ticketID)->with('project', 'project.client', 'states', 'states.allocated_user', 'states.author_user', 'states.status')->first()) {

                if (isset($ticket->states[0]->allocated_user) && $event->requesterUserID != $ticket->states[0]->allocated_user->id) {
                    $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TICKET_UPDATED', $ticket->states[0]->allocated_user->id);

                    if ($setting && boolval($setting->value) === true) {
                        $email = $ticket->states[0]->allocated_user->email;

                        Mail::send('projectsquare::emails.ticket_updated', array('ticket' => $ticket), function ($message) use ($email, $ticket) {
                            $message->to($email)
                                ->subject('[projectsquare] Modification du ticket : ' . $ticket->title);
                        });
                    }
                }
            }
        }
    }
}
