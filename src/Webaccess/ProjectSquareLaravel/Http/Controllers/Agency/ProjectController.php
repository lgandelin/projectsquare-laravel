<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Entities\Task;
use Webaccess\ProjectSquare\Requests\Phases\CreatePhaseRequest;
use Webaccess\ProjectSquare\Requests\Phases\DeletePhaseRequest;
use Webaccess\ProjectSquare\Requests\Phases\GetPhasesRequest;
use Webaccess\ProjectSquare\Requests\Phases\UpdatePhaseRequest;
use Webaccess\ProjectSquare\Requests\Planning\CreateEventRequest;
use Webaccess\ProjectSquare\Requests\Projects\CreateProjectRequest;
use Webaccess\ProjectSquare\Requests\Projects\UpdateProjectRequest;
use Webaccess\ProjectSquare\Requests\Tasks\CreateTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\DeleteTaskRequest;
use Webaccess\ProjectSquare\Requests\Tasks\UpdateTaskRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquare\Requests\Clients\GetClientsRequest;
use Webaccess\ProjectSquareLaravel\Http\Controllers\Utility\OccupationController;
use Webaccess\ProjectSquareLaravel\Tools\Calendar\Day;
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

        try {
            $project = app()->make('ProjectManager')->getProjectWithUsers($projectID);
            $phases = app()->make('GetPhasesInteractor')->execute(new GetPhasesRequest([
                'projectID' => $projectID
            ]));
            foreach ($phases as $phase) {
                $phase->tasks = app()->make('GetTasksInteractor')->getTasksByPhaseID($phase->id);
                $phase->duration = 0;

                foreach ($phase->tasks as $task) {
                    $phase->duration += $task->estimated_time_days;
                }
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('projects_index');
        }

        return view('projectsquare::agency.projects.edit', [
            'tab' => 'tasks',
            'project' => $project,
            'phases' => $phases,
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
            foreach ($phases as $phase) {
                $phase->tasks = app()->make('GetTasksInteractor')->getTasksByPhaseID($phase->id);
            }

            $users = app()->make('UserManager')->getUsersByRole(Input::get('filter_role'));
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return redirect()->route('projects_index');
        }

        return view('projectsquare::agency.projects.edit', [
            'tab' => 'attribution',
            'project' => $project,
            'phases' => $phases,

            'month_labels' => ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'calendars' => OccupationController::getCalendarsByUsers($users),
            'today' => (new DateTime())->setTime(0, 0, 0),
            'roles' => app()->make('RoleManager')->getRoles(),
            'filters' => [
                'role' => Input::get('filter_role'),
            ],

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

    public function update_tasks(Request $request)
    {
        parent::__construct($request);

        try {
            $phases = json_decode($request->phases);

            $phaseIDs = [];
            foreach ($phases as $i => $phase) {
                if ($phase->id == "") {
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
                    if ($task->id == "") {
                        app()->make('CreateTaskInteractor')->execute(new CreateTaskRequest([
                            'title' => $task->name,
                            'statusID' => Task::TODO,
                            'order' => ($j + 1),
                            'estimatedTimeDays' => (isset($task->duration) && $task->duration != "") ? $task->duration : null,
                            'phaseID' => ($phase->id == "") ? $response->phase->id : $phase->id,
                            'projectID' => $request->project_id,
                            'requesterUserID' => $this->getUser()->id
                        ]));
                    } else {
                        app()->make('UpdateTaskInteractor')->execute(new UpdateTaskRequest([
                            'taskID' => $task->id,
                            'title' => $task->name,
                            'statusID' => Task::TODO,
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

            $request->session()->flash('confirmation', trans('projectsquare::projects.edit_project_success'));

            return response()->json([
                'message' => trans('projectsquare::projects.edit_project_success')
            ], 200);
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::projects.edit_project_error'));

            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function allocate_task_in_planning(Request $request)
    {
        parent::__construct($request);

        try {
            $userID = Input::get('allocated_user_id') ? Input::get('allocated_user_id') : $this->getUser()->id;
            $user = app()->make('UserManager')->getUser($userID);

            $startTime = new \DateTime(Input::get('start_time') . ' 09:00:00');
            $endTime = clone $startTime;

            $durationInHours = Input::get('duration') * 8;

            $i = 0;
            while($i < $durationInHours) {
                $endTime->add(new \DateInterval ('PT1H'));

                //@TODO
                if ($endTime->format('H') <= 17 && $endTime->format('H') > 9 && $endTime->format('w') != Day::SATURDAY && $endTime->format('w') != Day::SUNDAY) {
                    $i++;
                }
            }

            app()->make('CreateEventInteractor')->execute(new CreateEventRequest([
                'name' => Input::get('name'),
                'userID' => $user->id,
                'startTime' => $startTime,
                'endTime' => $endTime,
                'projectID' => Input::get('project_id'),
                'taskID' => Input::get('task_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $users = app()->make('UserManager')->getUsersByRole(Input::get('filter_role'));

            $calendars = view('projectsquare::occupation.includes.calendar', [
                'month_labels' => ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                'calendars' => OccupationController::getCalendarsByUsers($users),
                'today' => (new DateTime())->setTime(0, 0, 0),
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
                'tasksScheduledTime' => StringTool::formatNumber(Input::get('tasks_scheduled_time')),
                'ticketsScheduledTime' => StringTool::formatNumber(Input::get('tickets_scheduled_time'))
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
}
