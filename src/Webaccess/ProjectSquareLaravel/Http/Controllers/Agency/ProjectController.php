<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Projects\CreateProjectRequest;
use Webaccess\ProjectSquare\Requests\Projects\UpdateProjectRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquare\Requests\Clients\GetClientsRequest;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class ProjectController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::agency.projects.index', [
            'projects' => app()->make('ProjectManager')->getProjectsPaginatedList(),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::agency.projects.add', [
            'clients' => app()->make('GetClientsInteractor')->execute(new GetClientsRequest()),
        ]);
    }

    public function store(Request $request)
    {
        parent::__construct($request);

        try {
            $response = app()->make('CreateProjectInteractor')->execute(new CreateProjectRequest([
                'name' => Input::get('name'),
                'clientID' => Input::get('client_id'),
                'color' => Input::get('color'),
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::projects.add_project_success'));

            return redirect()->route('projects_edit', ['id' => $response->project->id]);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.add_project_error'));

            return redirect()->route('projects_index');
        }
    }

    public function edit(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        try {
            $project = app()->make('ProjectManager')->getProjectWithUsers($projectID);
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('projects_index');
        }

        return view('projectsquare::agency.projects.edit', [
            'tab' => 'infos',
            'project' => $project,
            'clients' => app()->make('GetClientsInteractor')->execute(new GetClientsRequest()),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function edit_tasks(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;
    }

    public function edit_team(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;
    }

    public function edit_config(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        $settingAcceptableLoadingTime = app()->make('SettingManager')->getSettingByKeyAndProject('ACCEPTABLE_LOADING_TIME', $projectID);
        $settingAlertLoadingTimeEmail = app()->make('SettingManager')->getSettingByKeyAndProject('ALERT_LOADING_TIME_EMAIL', $projectID);
        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $projectID);
        $gaViewID = app()->make('SettingManager')->getSettingByKeyAndProject('GA_VIEW_ID', $projectID);

        try {
            $project = app()->make('ProjectManager')->getProjectWithUsers($projectID);
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('projects_index');
        }

        return view('projectsquare::agency.projects.edit', [
            'tab' => 'config',
            'project' => $project,
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
            'acceptable_loading_time' => ($settingAcceptableLoadingTime) ? $settingAcceptableLoadingTime->value : null,
            'alert_loading_time_email' => ($settingAlertLoadingTimeEmail) ? $settingAlertLoadingTimeEmail->value : null,
            'slack_channel' => ($settingSlackChannel) ? $settingSlackChannel->value : null,
            'ga_view_id' => ($gaViewID) ? $gaViewID->value : null,
        ]);
    }



    public function update(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('UpdateProjectInteractor')->execute(new UpdateProjectRequest([
                'projectID' => Input::get('project_id'),
                'name' => Input::get('name'),
                'clientID' => Input::get('client_id'),
                'color' => Input::get('color'),
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::projects.edit_project_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.edit_project_error'));
        }

        return redirect()->route('projects_edit', ['id' => Input::get('project_id')]);
    }

    public function update_config(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('UpdateProjectInteractor')->execute(new UpdateProjectRequest([
                'projectID' => Input::get('project_id'),
                'websiteFrontURL' => Input::get('website_front_url'),
                'websiteBackURL' => Input::get('website_back_url'),
                'tasksScheduledTime' => StringTool::formatNumber(Input::get('tasks_scheduled_time')),
                'ticketsScheduledTime' => StringTool::formatNumber(Input::get('tickets_scheduled_time'))
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::projects.edit_project_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.edit_project_error'));
        }

        return redirect()->route('projects_edit_config', ['id' => Input::get('project_id')]);
    }

    public function delete(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        try {
            app()->make('ProjectManager')->deleteProject($projectID);
            $request->session()->flash('confirmation', trans('projectsquare::projects.delete_project_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.delete_project_error'));
        }

        return redirect()->route('projects_index');
    }

    public function add_user(Request $request)
    {
        parent::__construct($request);

        try {
            $project = app()->make('ProjectManager')->getProject(Input::get('project_id'));
            $user = app()->make('UserManager')->getUser(Input::get('user_id'));
            $role = app()->make('RoleManager')->getRole(Input::get('role_id'));

            app()->make('ProjectManager')->addUserToProject(
                Input::get('project_id'),
                Input::get('user_id'),
                Input::get('role_id')
            );
            $request->session()->flash('confirmation', trans('projectsquare::projects.add_user_to_project_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->route('projects_edit', ['id' => Input::get('project_id')]);
    }

    public function delete_user(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;
        $userID = $request->user_id;

        try {
            $project = app()->make('ProjectManager')->getProject($projectID);
            $user = app()->make('UserManager')->getUser($userID);

            app()->make('ProjectManager')->removeUserFromProject($projectID, $userID);
            $request->session()->flash('confirmation', trans('projectsquare::projects.delete_user_from_project_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.delete_user_from_project_error'));
        }

        return redirect()->route('projects_edit', ['id' => $projectID]);
    }

    public function update_settings(Request $request)
    {
        parent::__construct($request);
        
        try {
            app()->make('SettingManager')->createOrUpdateProjectSetting(
                Input::get('project_id'),
                Input::get('key'),
                Input::get('value')
            );
            $request->session()->flash('confirmation', trans('projectsquare::project.update_setting_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::project.update_setting_error'));
        }

        return redirect()->route('projects_edit_config', ['id' => Input::get('project_id')]);
    }
}
