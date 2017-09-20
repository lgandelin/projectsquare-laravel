<?php

namespace Webaccess\ProjectSquareLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\DeleteTicketEvent;
use Webaccess\ProjectSquareLaravel\Models\Message;
use Webaccess\ProjectSquareLaravel\Models\Task;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentUserRepository;

class TicketDeletedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     *
     * @param DeleteTicketEvent $event
     */
    public function __construct(DeleteTicketEvent $event)
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
        if (isset($this->event->ticket->id) && $this->event->ticket->id) {
            if ($ticket = Ticket::where('id', '=', $this->event->ticket->id)->with('project', 'project.client', 'states', 'states.allocated_user', 'states.author_user')->first()) {

                if (isset($ticket->states[0]->allocated_user)) {
                    $setting = app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TICKET_DELETED', $ticket->states[0]->allocated_user->id);

                    if ($setting && boolval($setting->value) === true) {
                        $email = $ticket->states[0]->allocated_user->email;

                        Mail::send('projectsquare::emails.ticket_deleted', array('ticket' => $ticket), function ($message) use ($email, $ticket) {
                            $message->to($email)
                                ->subject('[projectsquare] Suppression du ticket : ' . $ticket->title);
                        });
                    }
                }
            }
        }
    }
}
