<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Webaccess\ProjectSquare\Requests\Notifications\GetUnreadNotificationsRequest;
use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\User;

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
        view()->share('notifications_count', sizeof($this->getUnreadNotifications()));
    }

    protected function getUser()
    {
        $user = Auth::user();

        return User::with('projects.client')->find($user->id);
    }

    protected function getUnreadNotifications()
    {
        if (Auth::user()) {
            return app()->make('GetNotificationsInteractor')->getUnreadNotifications(new GetUnreadNotificationsRequest([
                'userID' => Auth::user()->id,
            ]))->notifications;
        }

        return 0;
    }

    protected function getCurrentProject()
    {
        if (!$this->request->session()->has('current_project')) {
            $this->request->session()->set('current_project', Project::find(env('DEFAULT_PROJECT_ID', 1)));
        }

        return $this->request->session()->get('current_project');
    }
}
