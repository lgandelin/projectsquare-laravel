<?php

namespace Webaccess\ProjectSquareLaravel\Jobs\Tickets;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\CreateTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentUserRepository;

class TicketCreatedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     *
     * @param CreateTicketEvent $event
     */
    public function __construct(CreateTicketEvent $event)
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
            if ($ticket = Ticket::where('id', '=', $this->event->ticketID)->with('project', 'project.client', 'states', 'states.allocated_user')->first()) {

                if (isset($ticket->states[0]->allocated_user) && $ticket->states[0]->allocated_user != null) {
                    $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TICKET_CREATED', $ticket->states[0]->allocated_user->id);

                    if ($setting && boolval($setting->value) === true) {
                        $email = $ticket->states[0]->allocated_user->email;

                        Mail::send('projectsquare::emails.ticket_created', array('ticket' => $ticket), function ($message) use ($email, $ticket) {
                            $message->to($email)
                                ->subject('[projectsquare] Un nouveau ticket vous a été assigné');
                        });
                    }
                } else {
                    foreach ((new EloquentUserRepository())->getUsersByProject($ticket->project_id) as $user) {
                        $email = $user->email;

                        Mail::send('projectsquare::emails.ticket_created', array('ticket' => $ticket), function ($message) use ($email, $ticket) {
                            $message->to($email)
                                ->subject('[projectsquare] Un nouveau ticket a été créé');
                        });
                    }
                }
            }
        }
    }
}
