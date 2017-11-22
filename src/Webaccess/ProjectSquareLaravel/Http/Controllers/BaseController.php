<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Entities\Project as ProjectEntity;
use Webaccess\ProjectSquare\Requests\Notifications\GetUnreadNotificationsRequest;
use Webaccess\ProjectSquare\Requests\Todos\GetTodosRequest;
use Webaccess\ProjectSquareLaravel\Models\Client;
use Webaccess\ProjectSquareLaravel\Models\Platform;
use Webaccess\ProjectSquareLaravel\Models\Project;

class BaseController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('before_config');
        $this->middleware('auth');

        if (Auth::user()) {
            list($todos, $uncompleteTodos) = $this->getTodos();
            list($projects, $archived_projects) = $this->getProjects();

            view()->share('logged_in_user', $this->getUser());
            view()->share('in_progress_projects', $projects);
            view()->share('archived_projects', $archived_projects);
            view()->share('current_project', $this->getCurrentProject());
            view()->share('current_route', $request->route()->getName());
            view()->share('notifications', $this->getUnreadNotifications());
            view()->share('is_client', $this->isUserAClient());
            view()->share('is_admin', $this->isUserAnAdmin());
            view()->share('todos', $todos);
            view()->share('todos_count', $uncompleteTodos);
            view()->share('left_bar', isset($_COOKIE['left-bar']) ? $_COOKIE['left-bar'] : 'opened');
            view()->share('left_bar_projects', isset($_COOKIE['left-bar-projects']) ? $_COOKIE['left-bar-projects'] : 'opened');
            view()->share('left_bar_tools', isset($_COOKIE['left-bar-tools']) ? $_COOKIE['left-bar-tools'] : 'opened');
            view()->share('left_bar_management', isset($_COOKIE['left-bar-management']) ? $_COOKIE['left-bar-management'] : 'opened');
            view()->share('left_bar_administration', isset($_COOKIE['left-bar-administration']) ? $_COOKIE['left-bar-administration'] : 'opened');
        }
    }

    protected function getUser()
    {
        if ($user = Auth::user())
            return Auth::user();

        return null;
    }

    protected function getUnreadNotifications()
    {
        $notifications = [];

        if (Auth::user()) {
            $notifications = app()->make('GetNotificationsInteractor')->getUnreadNotifications(new GetUnreadNotificationsRequest([
                'userID' => Auth::user()->id,
            ]))->notifications;
        }

        return $notifications;
    }

    protected function getCurrentProject()
    {
        if ($this->isUserAClient()) {
            $client = Client::find($this->getUser()->client_id);
            if ($client && !$this->request->session()->has('current_project')) {
                $project = Project::where('client_id', '=', $client->id)->where('status_id', '=', ProjectEntity::IN_PROGRESS)->orderBy('created_at', 'DESC')->first();
                $this->request->session()->put('current_project', $project);
            }
        }

        return $this->request->session()->get('current_project');
    }

    protected function isUserAClient()
    {
        if ($this->getUser()) {
            return $this->getUser()->client_id != null;
        }

        return false;
    }

    protected function isUserAnAdmin()
    {
        if ($this->getUser()) {
            return $this->getUser()->is_administrator;
        }

        return false;
    }

    protected function betaForm()
    {
        $title = Input::get('title');
        $content = nl2br(Input::get('message'));
        $platform = Platform::first();

        Mail::send('projectsquare::emails.beta_form', array('title' => $title, 'content' => $content, 'user_last_name' => $this->getUser()->last_name, 'user_first_name' => $this->getUser()->first_name, 'platform_url' => $platform->url), function ($message) {
            $message->to('lgandelin@web-access.fr')
                ->subject('[projectsquare] Formulaire de contact');
        });

        return response()->json([
            'success' => true,
        ], 200);
    }

    protected function getTodos()
    {
        $todos = [];
        $uncompleteTodos = 0;

        if ($this->getUser()) {
            $todos = app()->make('GetTodosInteractor')->execute(new GetTodosRequest([
                'userID' => $this->getUser()->id
            ]));

            if (is_array($todos) && sizeof($todos) > 0) {
                foreach ($todos as $todo) {
                    if (!$todo->status) {
                        $uncompleteTodos++;
                    }
                }
            }
        }

        return [$todos, $uncompleteTodos];
    }

    protected function getProjects()
    {
        $projects = [];
        $archived_projects = [];

        foreach (Project::with('users', 'client')->orderBy('created_at', 'desc')->get() as $project) {

            if ($this->isUserAClient()) {
                if ($project->client_id == $this->getUser()->client_id) {
                    if ($project->status_id == ProjectEntity::IN_PROGRESS) {
                        $projects[] = $project;
                    } else {
                        $archived_projects[] = $project;
                    }
                }
            } else {
                foreach ($project->users as $user) {
                    if ($user->id == $this->getUser()->id) {
                        if ($project->status_id == ProjectEntity::IN_PROGRESS) {
                            $projects[] = $project;
                        } else {
                            $archived_projects[] = $project;
                        }
                    }
                }
            }
        }

        return array($projects, $archived_projects);
    }
}
