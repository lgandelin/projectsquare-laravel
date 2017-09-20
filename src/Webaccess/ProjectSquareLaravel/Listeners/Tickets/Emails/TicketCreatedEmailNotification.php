<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\CreateTicketEvent;
use Webaccess\ProjectSquare\Repositories\UserRepository;
use Webaccess\ProjectSquareLaravel\Jobs\TicketCreatedEmailJob;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentUserRepository;

class TicketCreatedEmailNotification
{
    public function handle(CreateTicketEvent $event)
    {
        TicketCreatedEmailJob::dispatch($event)->onQueue('emails');
    }
}
