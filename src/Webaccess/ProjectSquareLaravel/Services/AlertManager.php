<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentAlertRepository;

class AlertManager
{
    public static function createAlert($type, $variables, $projectID)
    {
        EloquentAlertRepository::createAlert($type, $variables, $projectID);
    }

    public function deleteAlertByProjectID($projectID)
    {
        EloquentAlertRepository::deleteAlertByProjectID($projectID);
    }

    public static function deleteOldAlerts()
    {
        EloquentAlertRepository::deleteOldAlerts();
    }
}
