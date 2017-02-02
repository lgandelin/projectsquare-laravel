<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Http\Request;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class SettingsController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        $slack = app()->make('SettingManager')->getSettingByKey('SLACK_URL');

        return view('projectsquare::agency.settings.index', [
            'slack' => ($slack) ? $slack->value : null,
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('SettingManager')->createOrUpdateSetting(
                null,
                $request->key,
                $request->value
            );
            $request->session()->flash('confirmation', trans('projectsquare::settings.update_setting_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::settings.update_setting_error'));
        }

        return redirect()->route('settings_index');
    }
}