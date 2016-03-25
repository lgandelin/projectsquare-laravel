<?php

namespace Webaccess\ProjectSquareLaravel\Events;

use Illuminate\Queue\SerializesModels;
use Webaccess\ProjectSquareLaravel\Models\Request;

class AlertWebsiteLoadingTimeEvent
{
    use SerializesModels;

    public $ticket;
    public $email;

    public function __construct(Request $request, $email, $loadingTime)
    {
        $this->request = $request;
        $this->email = $email;
        $this->loading_time = $loadingTime;
    }
}
