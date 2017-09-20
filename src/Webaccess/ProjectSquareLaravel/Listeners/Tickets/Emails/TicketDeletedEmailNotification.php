<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Tickets\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Tickets\DeleteTicketEvent;
use Webaccess\ProjectSquareLaravel\Jobs\TicketDeletedEmailJob;
use Webaccess\ProjectSquareLaravel\Models\Ticket;

class TicketDeletedEmailNotification
{
    public function handle(DeleteTicketEvent $event)
    {
        TicketDeletedEmailJob::dispatch($event)->onQueue('emails');
    }
}
