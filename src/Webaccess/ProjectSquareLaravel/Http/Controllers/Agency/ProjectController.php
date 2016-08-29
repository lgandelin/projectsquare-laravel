<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class ProjectController extends BaseController
{
    public function index()
    {
        return view('projectsquare::agency.projects.index', [
            'projects' => app()->make('ProjectManager')->getProjectsPaginatedList(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function add()
    {
        return view('projectsquare::agency.projects.add', [
            'clients' => app()->make('ClientManager')->getClients(),
        ]);
    }

    public function store()
    {
        try {
            $projectID = app()->make('ProjectManager')->createProject(
                Input::get('name'),
                Input::get('client_id'),
                Input::get('website_front_url'),
                Input::get('website_back_url'),
                $this->getUser()->id,
                Input::get('status'),
                Input::get('color'),
                Input::get('tasks_scheduled_time'),
                Input::get('tickets_scheduled_time')
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::projects.add_project_success'));

            return redirect()->route('projects_edit', ['id' => $projectID]);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::projects.add_project_error'));

            return redirect()->route('projects_index');
        }

    }

    public function edit($projectID)
    {
        $settingAcceptableLoadingTime = app()->make('SettingManager')->getSettingByKeyAndProject('ACCEPTABLE_LOADING_TIME', $projectID);
        $settingAlertLoadingTimeEmail = app()->make('SettingManager')->getSettingByKeyAndProject('ALERT_LOADING_TIME_EMAIL', $projectID);
        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $projectID);
        $gaViewID = app()->make('SettingManager')->getSettingByKeyAndProject('GA_VIEW_ID', $projectID);

        try {
            $project = app()->make('ProjectManager')->getProjectWithUsers($projectID);
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());

            return redirect()->route('projects_index');
        }

        return view('projectsquare::agency.projects.edit', [
            'project' => $project,
            'clients' => app()->make('ClientManager')->getClients(),
            'roles' => app()->make('RoleManager')->getRoles(),
            'users' => app()->make('UserManager')->getAgencyUsers(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
            'acceptable_loading_time' => ($settingAcceptableLoadingTime) ? $settingAcceptableLoadingTime->value : null,
            'alert_loading_time_email' => ($settingAlertLoadingTimeEmail) ? $settingAlertLoadingTimeEmail->value : null,
            'slack_channel' => ($settingSlackChannel) ? $settingSlackChannel->value : null,
            'ga_view_id' => ($gaViewID) ? $gaViewID->value : null,
        ]);
    }

    public function update()
    {
        try {
            app()->make('ProjectManager')->updateProject(
                Input::get('project_id'),
                Input::get('name'),
                Input::get('client_id'),
                Input::get('website_front_url'),
                Input::get('website_back_url'),
                Input::get('status'),
                Input::get('color'),
                Input::get('tasks_scheduled_time'),
                Input::get('tickets_scheduled_time')
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::projects.edit_project_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::projects.edit_project_error'));
        }

        return redirect()->route('projects_edit', ['id' => Input::get('project_id')]);
    }

    public function delete($projectID)
    {
        try {
            app()->make('ProjectManager')->deleteProject($projectID);
            $this->request->session()->flash('confirmation', trans('projectsquare::projects.delete_project_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::projects.delete_project_error'));
        }

        return redirect()->route('projects_index');
    }

    public function add_user()
    {
        try {
            $project = app()->make('ProjectManager')->getProject(Input::get('project_id'));
            $user = app()->make('UserManager')->getUser(Input::get('user_id'));
            $role = app()->make('RoleManager')->getRole(Input::get('role_id'));

            app()->make('ProjectManager')->addUserToProject(
                Input::get('project_id'),
                Input::get('user_id'),
                Input::get('role_id')
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::projects.add_user_to_project_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('projects_edit', ['id' => Input::get('project_id')]);
    }

    public function delete_user($projectID, $userID)
    {
        try {
            $project = app()->make('ProjectManager')->getProject($projectID);
            $user = app()->make('UserManager')->getUser($userID);

            app()->make('ProjectManager')->removeUserFromProject($projectID, $userID);
            $this->request->session()->flash('confirmation', trans('projectsquare::projects.delete_user_from_project_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::projects.delete_user_from_project_error'));
        }

        return redirect()->route('projects_edit', ['id' => $projectID]);
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

        return redirect()->route('projects_edit', ['id' => Input::get('project_id')]);
    }
}
