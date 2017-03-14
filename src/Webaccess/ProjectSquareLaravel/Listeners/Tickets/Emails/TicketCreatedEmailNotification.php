<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\CreateTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;

class TicketCreatedEmailNotification
{
    public function handle(CreateTicketEvent $event)
    {
        if (isset($event->ticketID) && $event->ticketID) {
            if ($ticket = Ticket::where('id', '=', $event->ticketID)->with('project', 'project.client', 'states', 'states.allocated_user')->first()) {

                if (isset($ticket->states[0]->allocated_user)) {
                    $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TICKET_CREATED', $ticket->states[0]->allocated_user->id);

                    if ($setting && boolval($setting->value) === true) {
                        $email = $ticket->states[0]->allocated_user->email;

                        Mail::send('projectsquare::emails.ticket_created', array('ticket' => $ticket), function ($message) use ($email, $ticket) {
                            $message->to($email)
                                ->from('no-reply@projectsquare.io')
                                ->subject('[projectsquare] Un nouveau ticket vous a été assigné');
                        });
                    }
                }
            }
        }
    }
}