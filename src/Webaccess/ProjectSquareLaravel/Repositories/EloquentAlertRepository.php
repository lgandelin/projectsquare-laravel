<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquareLaravel\Models\Alert;
use Webaccess\ProjectSquare\Repositories\AlertRepository;

class EloquentAlertRepository implements AlertRepository
{
    public static function getLastAlerts($limit)
    {
        $alerts = Alert::orderBy('updated_at', 'DESC')->limit($limit)->get();
        foreach ($alerts as $alert) {
            $alert->variables = json_decode($alert->variables);
        }

        return $alerts;
    }

    public static function createAlert($type, $variables, $projectID)
    {
        $alert = new Alert();
        $alert->type = $type;
        $alert->variables = json_encode($variables);
        $alert->project_id = $projectID;
        $alert->save();
    }
}
