<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Webaccess\ProjectSquare\Interactors\Messages\GetUnreadMessagesInteractor;
use Webaccess\ProjectSquare\Requests\Messages\GetUnreadMessagesRequest;
use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\User;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentUserRepository;

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
    }

    protected function getUser()
    {
        $user = Auth::user();
        $user = User::with('projects.client')->find($user->id);

        $unreadMessages = (new GetUnreadMessagesInteractor(new EloquentUserRepository()))->execute(new GetUnreadMessagesRequest([
            'userID' => $user->id,
        ]))->messages;

        $user->unread_messages_count = count($unreadMessages);

        return $user;
    }

    protected function getCurrentProject()
    {
        if (!$this->request->session()->has('current_project')) {
            $this->request->session()->set('current_project', Project::find(env('DEFAULT_PROJECT_ID', 1)));
        }

        return $this->request->session()->get('current_project');
    }
}
