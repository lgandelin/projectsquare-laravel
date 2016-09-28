<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentSettingRepository;

class SettingManager
{
    public static function getSettingByKey($key)
    {
        return EloquentSettingRepository::getSettingByKey($key);
    }

    public static function getSettingByKeyAndProject($key, $projectID)
    {
        return EloquentSettingRepository::getSettingByKeyAndProject($key, $projectID);
    }

    public static function createOrUpdateSetting($projectID, $key, $value)
    {
        return EloquentSettingRepository::createOrUpdateSetting($projectID, $key, $value);
    }
}
