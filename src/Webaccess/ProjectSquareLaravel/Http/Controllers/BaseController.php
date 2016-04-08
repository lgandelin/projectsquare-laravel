<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Webaccess\ProjectSquare\Requests\Notifications\GetUnreadNotificationsRequest;
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
    }

    protected function getUser()
    {
        $user = Auth::user();

        return User::with('projects.client')->find($user->id);
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
        return $this->request->session()->get('current_project');
    }
}
