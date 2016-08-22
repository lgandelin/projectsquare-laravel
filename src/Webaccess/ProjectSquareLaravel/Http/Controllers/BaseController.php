<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquare\Requests\Notifications\GetUnreadNotificationsRequest;
use Webaccess\ProjectSquareLaravel\Models\Client;
use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\User;
use Webaccess\ProjectSquareLaravel\Decorators\NotificationDecorator;
use Webaccess\ProjectSquare\Requests\Todos\GetTodosRequest;

class BaseController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('before_install');
        $this->middleware('auth');

        if (Auth::user()) {
            view()->share('logged_in_user', $this->getUser());
        }

        view()->share('current_project', $this->getCurrentProject());
        view()->share('current_route', $request->path());
        view()->share('notifications', $this->getUnreadNotifications());
        view()->share('is_client', $this->isUserAClient());
        view()->share('is_admin', $this->isUserAnAdmin());
        view()->share('todos', $this->getTodos());
        view()->share('todos_count', $this->getUncompleteTodosCount());
        view()->share('left_bar', isset($_COOKIE['left-bar']) ? $_COOKIE['left-bar'] : 'opened');
    }

    protected function getUser()
    {
        if ($user = Auth::user()) {
            return User::with('projects.client')->find($user->id);
        }

        return null;
    }

    protected function getUnreadNotifications()
    {
        if (Auth::user()) {
            $notifications = app()->make('GetNotificationsInteractor')->getUnreadNotifications(new GetUnreadNotificationsRequest([
                'userID' => Auth::user()->id,
            ]))->notifications;

            return (new NotificationDecorator())->decorate($notifications);
        }

        return 0;
    }

    protected function getCurrentProject()
    {
        if ($this->isUserAClient()) {
            if ($client = Client::find($this->getUser()->client_id)) {
                $project = Project::where('client_id', '=', $client->id)->first();
                $this->request->session()->set('current_project', $project);
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
        $userID = $this->getUser()->id;
        $title = Input::get('title');
        $content = nl2br(Input::get('message'));

        Mail::send('projectsquare::emails.beta_form', array('title' => $title, 'content' => $content, 'user_id' => $userID), function ($message) {
            $message->to('lgandelin@web-access.fr')
                ->from('no-reply@projectsquare.fr')
                ->subject('[projectsquare] Formulaire de contact');
        });

        return response()->json([
            'success' => true,
        ], 200);
    }

    protected function getTodos()
    {
        if ($this->getUser()) {
            return app()->make('GetTodosInteractor')->execute(new GetTodosRequest([
                'userID' => $this->getUser()->id
            ]));
        }

        return []; 
    }

    private function getUncompleteTodosCount()
    {
        $result = 0;
        $todos = $this->getTodos();
        if (is_array($todos) && sizeof($todos) > 0) {
            foreach ($todos as $todo) {
                if (!$todo->status) {
                    $result++;
                }
            }
        }

        return $result;
    }
}
