<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Messages\CreateConversationEvent;

class ConversationCreatedEmailNotification
{
    public function handle(CreateConversationEvent $event)
    {
    }
}
