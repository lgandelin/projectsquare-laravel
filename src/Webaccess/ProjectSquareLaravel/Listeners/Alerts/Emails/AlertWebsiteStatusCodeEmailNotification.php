<?php

namespace Webaccess\ProjectSquareLaravel\Listeners\Alerts\Emails;

use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Events\Alerts\AlertWebsiteStatusCodeEvent;

class AlertWebsiteStatusCodeEmailNotification
{
    public function handle(AlertWebsiteStatusCodeEvent $event)
    {
        $request = $event->request;
        $email = $event->email;
        $statusCode = $event->statusCode;
        $project = app()->make('ProjectManager')->getProject($event->request->project_id);

        Mail::send('projectsquare::emails.alert_status_code', ['request' => $request, 'project' => $project], function ($m) use ($request, $project, $email, $statusCode) {
            $m->to($email)->subject('['.$project->clientName.' - ' . $project->name . '] Le site internet a retourné une erreur (code : '.$statusCode.')');
        });
    }
}
