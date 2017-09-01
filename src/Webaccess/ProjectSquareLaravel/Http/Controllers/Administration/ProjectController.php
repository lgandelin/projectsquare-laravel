<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Administration;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Entities\Task;
use Webaccess\ProjectSquare\Requests\Phases\CreatePhaseRequest;
use Webaccess\ProjectSquare\Requests\Phases\CreatePhasesAndTasksFromTextRequest;
use Webaccess\ProjectSquare\Requests\Phases\DeletePhaseRequest;
use Webaccess\ProjectSquare\Requests\Phases\GetPhasesRequest;
use Webaccess\ProjectSquare\Requests\Phases\UpdatePhaseRequest;
use Webaccess\ProjectSquare\Requests\Projects\CreateProjectRequest;
use Webaccess\ProjectSquare\Requests\Projects\UpdateProjectRequest;
use Webaccess\ProjectSquare\Requests\Tasks\AllocateAndScheduleTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\CreateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\DeleteTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\UpdateTaskRequest;
use Webaccess\ProjectSquare\Requests\Users\AddUserToProjectRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquare\Requests\Clients\GetClientsRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\Management\OccupationController;
use Webaccess\ProjectSquareLaravel\Models\Role;
use Webaccess\ProjectSquareLaravel\Tools\StringTool;

class ProjectController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        $itemsPerPage = $request->get('it') ? $request->get('it') : env('PROJECTS_PER_PAGE', 10);

        return view('projectsquare::administration.projects.index', [
            'items_per_page' => $request->get('it') ? $request->get('it') : $itemsPerPage,
            'sort_column' => $request->get('sc'),
            'sort_order' => ($request->get('so') == 'asc') ? 'desc' : 'asc',
            'projects' => app()->make('ProjectManager')->getProjectsPaginatedList($itemsPerPage, $request->get('sc'), $request->get('so')),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function add(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::administration.projects.add', [
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
                'statusID' => Input::get('status_id'),
            ]));

            app()->make('AddUserToProjectInteractor')->execute(new AddUserToProjectRequest([
                'projectID' => $response->project->id,
                'userID' => $this->getUser()->id,
                'roleID' => Role::first()->id
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::projects.add_project_success'));

            return redirect()->route('projects_edit_team', ['uuid' => $response->project->id])->withCookie(cookie('creating_project_' . $response->project->id, true, 10));
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

        return view('projectsquare::administration.projects.edit', [
            'tab' => 'infos',
            'project' => $project,
            'clients' => app()->make('GetClientsInteractor')->execute(new GetClientsRequest()),
            'creating_project' => $request->cookie('creating_project_' . $project->id),

            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function edit_team(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        try {
            $project = app()->make('ProjectManager')->getProjectWithUsers($projectID);
            $roles = app()->make('RoleManager')->getRoles();

            $userIDs = [];
            foreach ($project->users as $user) {
                $userIDs[]= $user->id;
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('projects_index');
        }

        return view('projectsquare::administration.projects.edit', [
            'tab' => 'team',
            'users' => app()->make('UserManager')->getAgencyUsersGroupedByRoles($roles),
            'project' => $project,
            'creating_project' => $request->cookie('creating_project_' . $project->id),
            'userIDs' => $userIDs,

            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function edit_tasks(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        try {
            $project = app()->make('ProjectManager')->getProjectWithUsers($projectID);
            $phases = app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $projectID
            ]));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('projects_index');
        }

        return view('projectsquare::administration.projects.edit', [
            'tab' => 'tasks',
            'project' => $project,
            'phases' => $phases,
            'creating_project' => $request->cookie('creating_project_' . $project->id),

            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function edit_attribution(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->uuid;

        try {
            $project = app()->make('ProjectManager')->getProjectWithUsers($projectID);
            $phases = app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $projectID
            ]));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('projects_index');
        }

        return view('projectsquare::administration.projects.edit', [
            'tab' => 'attribution',
            'project' => $project,
            'phases' => $phases,
            'month_labels' => OccupationController::getMonthLabels(),
            'calendars' => OccupationController::getUsersCalendarsByRole(Input::get('filter_role')),
            'roles' => app()->make('RoleManager')->getRoles(),
            'filters' => [
                'role' => Input::get('filter_role'),
            ],
            'creating_project' => $request->cookie('creating_project_' . $project->id),

            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
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

        return view('projectsquare::administration.projects.edit', [
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
                'statusID' => Input::get('status_id'),
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::projects.edit_project_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.edit_project_error'));
        }

        return redirect()->route('projects_edit_team', ['uuid' => Input::get('project_id')]);
    }

    public function update_tasks(Request $request)
    {
        parent::__construct($request);

        try {
            $phases = json_decode($request->phases);
            $phaseIDs = [];
            foreach ($phases as $i => $phase) {
                if (isset($phase->is_new) && $phase->is_new == "1") {
                    $response = app()->make('CreatePhaseInteractor')->execute(new CreatePhaseRequest([
                        'name' => $phase->name,
                        'projectID' => $request->project_id,
                        'order' => ($i + 1),
                        'requesterUserID' => $this->getUser()->id
                    ]));
                    $phaseIDs[]= $response->phase->id;
                } else {
                    $response = app()->make('UpdatePhaseInteractor')->execute(new UpdatePhaseRequest([
                        'phaseID' => $phase->id,
                        'name' => $phase->name,
                        'order' => ($i + 1),
                        'requesterUserID' => $this->getUser()->id
                    ]));
                    $phaseIDs[]= $phase->id;
                }

                foreach ($phase->tasks as $j => $task) {
                    if (isset($task->is_new) && $task->is_new == "1") {
                        app()->make('CreateTaskInteractor')->execute(new CreateTaskRequest([
                            'title' => $task->name,
                            'statusID' => Task::TODO,
                            'order' => ($j + 1),
                            'estimatedTimeDays' => (isset($task->duration) && $task->duration != "") ? $task->duration : null,
                            'phaseID' => (isset($phase->is_new) && $phase->is_new == "1") ? $response->phase->id : $phase->id,
                            'projectID' => $request->project_id,
                            'requesterUserID' => $this->getUser()->id
                        ]));
                    } else {
                        app()->make('UpdateTaskInteractor')->execute(new UpdateTaskRequest([
                            'taskID' => $task->id,
                            'title' => $task->name,
                            'allocatedUserID' => 0,
                            'order' => ($j + 1),
                            'estimatedTimeDays' => (isset($task->duration) && $task->duration != "") ? $task->duration : null,
                            'requesterUserID' => $this->getUser()->id,
                        ]));
                    }
                }
            }

            if ($request->phase_ids_to_delete != "") {
                $phaseIDs = explode(',', $request->phase_ids_to_delete);

                foreach ($phaseIDs as $phaseID) {
                    if ($phaseID != "") {
                        app()->make('DeletePhaseInteractor')->execute(new DeletePhaseRequest([
                            'phaseID' => $phaseID,
                            'requesterUserID' => $this->getUser()->id
                        ]));
                    }
                }
            }

            if ($request->task_ids_to_delete != "") {
                $taskIDs = explode(',', $request->task_ids_to_delete);

                foreach ($taskIDs as $taskID) {
                    if ($taskID != "") {
                        app()->make('DeleteTaskInteractor')->execute(new DeleteTaskRequest([
                            'taskID' => $taskID,
                            'requesterUserID' => $this->getUser()->id
                        ]));
                    }
                }
            }

            $request->session()->flash('confirmation', trans('projectsquare::projects.edit_tasks_success'));

            return response()->json([
                'message' => trans('projectsquare::projects.edit_project_success'),
                'redirection_url' => route('projects_edit_attribution', ['uuid' => $request->project_id])
            ], 200);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.edit_tasks_error'));

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function import_phases_and_tasks_from_text(Request $request)
    {
        parent::__construct($request);

        $projectID = Input::get('project_id');

        try {
            app()->make('CreatePhasesAndTasksFromTextInteractor')->execute(new CreatePhasesAndTasksFromTextRequest([
                'text' => Input::get('text'),
                'projectID' => $projectID,
                'requesterUserID' => $this->getUser()->id,
            ]));

            $request->session()->flash('confirmation', trans('projectsquare::projects.import_phases_and_tasks_from_text_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.import_phases_and_tasks_from_text_error'));
        }

        return redirect()->route('projects_edit_attribution', ['uuid' => $projectID]);
    }

    public function allocate_and_schedule_task(Request $request)
    {
        parent::__construct($request);

        Cookie::queue(Cookie::forget('creating_project_' . Input::get('project_id')));

        try {
            $userID = Input::get('allocated_user_id') ? Input::get('allocated_user_id') : $this->getUser()->id;
            $user = app()->make('UserManager')->getUser($userID);

            app()->make('AllocateAndScheduleTaskInteractor')->execute(new AllocateAndScheduleTaskRequest([
                'userID' => $user->id,
                'day' => new \DateTime(Input::get('start_time')),
                'taskID' => Input::get('task_id'),
                'requesterUserID' => $user->id,
            ]));

            $calendars = view('projectsquare::management.occupation.includes.calendar', [
                'month_labels' => OccupationController::getMonthLabels(),
                'calendars' => OccupationController::getUsersCalendarsByRole(Input::get('filter_role')),
            ])->render();

            $avatar = view('projectsquare::includes.avatar', [
                'id' => $user->id,
                'name' => $user->lastName . ' ' . $user->firstName
            ])->render();

            return response()->json([
                'avatar' => $avatar,
                'calendars' => $calendars
            ], 200);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.edit_project_error'));

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update_config(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('UpdateProjectInteractor')->execute(new UpdateProjectRequest([
                'projectID' => Input::get('project_id'),
                'websiteFrontURL' => Input::get('website_front_url'),
                'websiteBackURL' => Input::get('website_back_url'),
            ]));

            app()->make('SettingManager')->createOrUpdateProjectSetting(
                Input::get('project_id'),
                'ACCEPTABLE_LOADING_TIME',
                Input::get('ACCEPTABLE_LOADING_TIME')
            );

            app()->make('SettingManager')->createOrUpdateProjectSetting(
                Input::get('project_id'),
                'ALERT_LOADING_TIME_EMAIL',
                Input::get('ALERT_LOADING_TIME_EMAIL')
            );

            app()->make('SettingManager')->createOrUpdateProjectSetting(
                Input::get('project_id'),
                'SLACK_CHANNEL',
                Input::get('SLACK_CHANNEL')
            );

            app()->make('SettingManager')->createOrUpdateProjectSetting(
                Input::get('project_id'),
                'GA_VIEW_ID',
                Input::get('GA_VIEW_ID')
            );

            $request->session()->flash('confirmation', trans('projectsquare::projects.edit_project_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.edit_project_error'));
        }

        return redirect()->route('projects_edit_config', ['uuid' => Input::get('project_id')]);
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

    public function update_team(Request $request)
    {
        parent::__construct($request);

        $projectID = $request->project_id;
        $userIDs = json_decode($request->user_ids);

        try {
            $project = app()->make('ProjectManager')->getProjectWithUsers($projectID);

            $projectUserIDs = [];
            foreach ($project->users as $user) {
                $projectUserIDs[]= $user->id;
            }

            foreach ($userIDs as $userID) {
                if (!in_array($userID, $projectUserIDs)) {
                    $user = app()->make('UserManager')->getUser($userID);

                    app()->make('AddUserToProjectInteractor')->execute(new AddUserToProjectRequest([
                        'projectID' => $projectID,
                        'userID' => $userID,
                        'roleID' => $user->roleID,
                        'requesterUserID' => $this->getUser()->id
                    ]));
                }
            }

            $removedUserIDs = array_diff($projectUserIDs, $userIDs);
            foreach ($removedUserIDs as $userID) {
                app()->make('ProjectManager')->removeUserFromProject($projectID, $userID, $this->getUser()->id);
            }

            $request->session()->flash('confirmation', trans('projectsquare::projects.edit_team_success'));

        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.edit_team_error'));
            return redirect()->route('projects_edit_team', ['uuid' => $projectID]);
        }

        if ($request->cookie('creating_project_' . $project->id)) {
            return redirect()->route('projects_edit_tasks', ['uuid' => $projectID]);
        }

        return redirect()->route('projects_edit_team', ['uuid' => $projectID]);
    }
}
