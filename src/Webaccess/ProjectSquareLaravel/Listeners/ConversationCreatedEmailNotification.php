<?php

namespace Webaccess\ProjectSquareLaravel\Listeners;

use Webaccess\ProjectSquare\Events\Conversations\CreateConversationEvent;

class ConversationCreatedEmailNotification
{
    public function handle(CreateConversationEvent $event)
    {
    }
}
