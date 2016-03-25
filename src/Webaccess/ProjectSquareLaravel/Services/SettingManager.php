<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentSettingRepository;

class SettingManager
{
    public static function getSettingByKeyAndProject($key, $projectID)
    {
        return EloquentSettingRepository::getSettingByKeyAndProject($key, $projectID);
    }

    public static function createOrUpdateSetting($projectID, $key, $value)
    {
        EloquentSettingRepository::createOrUpdateSetting($projectID, $key, $value);
    }
}
