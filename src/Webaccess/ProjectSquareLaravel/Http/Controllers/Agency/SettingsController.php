<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Support\Facades\Request;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class SettingsController extends BaseController
{
    public function index()
    {
        $slack = app()->make('SettingManager')->getSettingByKey('SLACK_URL');

        return view('projectsquare::agency.settings.index', [
            'slack' => ($slack) ? $slack->value : null,
        ]);
    }

    public function update()
    {
        try {
            app()->make('SettingManager')->createOrUpdateSetting(
                null,
                $this->request->key,
                $this->request->value
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::settings.update_setting_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::settings.update_setting_error'));
        }

        return redirect()->route('settings_index');
    }
}