<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquareLaravel\Models\Alert;
use Webaccess\ProjectSquare\Repositories\AlertRepository;

class EloquentAlertRepository implements AlertRepository
{
    public static function getAlertsPaginatedList($limit=null)
    {
        return Alert::orderBy('updated_at', 'DESC')->paginate($limit);
    }

    public static function createAlert($type, $variables, $projectID)
    {
        $alert = new Alert();
        $alert->type = $type;
        $alert->variables = json_encode($variables);
        $alert->project_id = $projectID;
        $alert->save();
    }

    public static function deleteAlertByProjectID($projectID)
    {
        Alert::where('project_id', '=', $projectID)->delete();
    }
}
