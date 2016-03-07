<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentAlertRepository;

class AlertManager
{
    public static function getLastAlerts($limit)
    {
        return EloquentAlertRepository::getLastAlerts($limit);
    }

    public static function createAlert($type, $variables, $projectID)
    {
        EloquentAlertRepository::createAlert($type, $variables, $projectID);
    }
}
