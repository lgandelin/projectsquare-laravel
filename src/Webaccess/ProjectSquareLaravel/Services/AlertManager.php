<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentAlertRepository;

class AlertManager
{
    public static function getAlertsPaginatedList()
    {
        return EloquentAlertRepository::getAlertsPaginatedList(10);
    }

    public static function createAlert($type, $variables, $projectID)
    {
        EloquentAlertRepository::createAlert($type, $variables, $projectID);
    }

    public function deleteAlertByProjectID($projectID)
    {
        EloquentAlertRepository::deleteAlertByProjectID($projectID);
    }
}
