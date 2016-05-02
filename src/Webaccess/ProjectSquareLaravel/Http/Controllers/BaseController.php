<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Webaccess\ProjectSquare\Requests\Notifications\GetUnreadNotificationsRequest;
use Webaccess\ProjectSquareLaravel\Models\Client;
use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\User;
use Webaccess\ProjectSquareLaravel\Decorators\NotificationDecorator;

class BaseController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');

        if (Auth::user()) {
            view()->share('logged_in_user', $this->getUser());
        }

        view()->share('current_project', $this->getCurrentProject());
        view()->share('notifications', $this->getUnreadNotifications());
        view()->share('is_client', $this->isUserAClient());
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
            $client = Client::find($this->getUser()->client_id);
            $project = Project::where('client_id', '=', $client->id)->first();

            $this->request->session()->set('current_project', $project);
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
}
