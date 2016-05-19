<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Project;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class SettingsController extends BaseController
{
    public function index($projectID)
    {
        $settingAcceptableLoadingTime = app()->make('SettingManager')->getSettingByKeyAndProject('ACCEPTABLE_LOADING_TIME', $projectID);
        $settingAlertLoadingTimeEmail = app()->make('SettingManager')->getSettingByKeyAndProject('ALERT_LOADING_TIME_EMAIL', $projectID);
        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $projectID);
        $gaViewID = app()->make('SettingManager')->getSettingByKeyAndProject('GA_VIEW_ID', $projectID);

        return view('projectsquare::project.settings', [
            'acceptable_loading_time' => ($settingAcceptableLoadingTime) ? $settingAcceptableLoadingTime->value : null,
            'alert_loading_time_email' => ($settingAlertLoadingTimeEmail) ? $settingAlertLoadingTimeEmail->value : null,
            'slack_channel' => ($settingSlackChannel) ? $settingSlackChannel->value : null,
            'ga_view_id' => ($gaViewID) ? $gaViewID->value : null,
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update_settings()
    {
        try {
            app()->make('SettingManager')->createOrUpdateSetting(
                Input::get('project_id'),
                Input::get('key'),
                Input::get('value')
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::settings.update_setting_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::settings.update_setting_error'));
        }

        return redirect()->route('project_settings', ['id' => Input::get('project_id')]);
    }
}