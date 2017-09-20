<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\UpdateTicketEvent;
use Webaccess\ProjectSquareLaravel\Jobs\TicketUpdatedEmailJob;
use Webaccess\ProjectSquareLaravel\Models\Ticket;

class TicketUpdatedEmailNotification
{
    public function handle(UpdateTicketEvent $event)
    {
        TicketUpdatedEmailJob::dispatch($event)->onQueue('emails');
    }
}
