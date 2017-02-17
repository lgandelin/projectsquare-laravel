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

    public static function getSettingByKeyAndUser($key, $userID)
    {
        return EloquentSettingRepository::getSettingByKeyAndUser($key, $userID);
    }

    public static function createOrUpdateProjectSetting($projectID, $key, $value)
    {
        EloquentSettingRepository::createOrUpdateProjectSetting($projectID, $key, $value);
    }

    public static function createOrUpdateUserSetting($userID, $key, $value)
    {
        EloquentSettingRepository::createOrUpdateUserSetting($userID, $key, $value);
    }
}
