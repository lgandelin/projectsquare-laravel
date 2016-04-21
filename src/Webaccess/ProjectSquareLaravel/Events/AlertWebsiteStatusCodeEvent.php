<?php

namespace Webaccess\ProjectSquareLaravel\Events;

use Illuminate\Queue\SerializesModels;
use Webaccess\ProjectSquareLaravel\Models\Request;

class AlertWebsiteStatusCodeEvent
{
    use SerializesModels;

    public function __construct(Request $request, $email, $statusCode)
    {
        $this->request = $request;
        $this->email = $email;
        $this->status_code = $statusCode;
    }
}
