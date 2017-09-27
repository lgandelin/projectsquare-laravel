<?php

namespace Webaccess\ProjectSquareLaravel\Jobs\Tickets;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\UpdateTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;

class TicketUpdatedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     *
     * @param UpdateTicketEvent $event
     */
    public function __construct(UpdateTicketEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (isset($this->event->ticketID) && $this->event->ticketID) {
            if ($ticket = Ticket::where('id', '=', $this->event->ticketID)->with('project', 'project.client', 'states', 'states.allocated_user', 'states.author_user', 'states.status')->first()) {

                if (isset($ticket->states[0]->allocated_user) && $this->event->requesterUserID != $ticket->states[0]->allocated_user->id) {
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
