<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquareLaravel\Models\Setting;
use Webaccess\ProjectSquare\Repositories\SettingRepository;

class EloquentSettingRepository implements SettingRepository
{
    public static function getSettingByKeyAndProject($key, $projectID)
    {
        return Setting::where('key', '=', $key)->where('project_id', '=', $projectID)->first();
    }

    public static function createOrUpdateSetting($projectID, $key, $value)
    {
        if (!$setting = self::getSettingByKeyAndProject($key, $projectID)) {
            $setting = new Setting();
        }

        $setting->key = $key;
        $setting->value = $value;
        $setting->project_id = $projectID;
        $setting->save();
    }

    public static function getSettingByKey($key)
    {
        return Setting::where('key', '=', $key)->first();
    }
}
