<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Administration;

use Illuminate\Http\Request;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquareLaravel\Models\Client;
use Webaccess\ProjectSquareLaravel\Models\Conversation;
use Webaccess\ProjectSquareLaravel\Models\Event;
use Webaccess\ProjectSquareLaravel\Models\Message;
use Webaccess\ProjectSquareLaravel\Models\Notification;
use Webaccess\ProjectSquareLaravel\Models\Phase;
use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\Task;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquareLaravel\Models\TicketState;
use Webaccess\ProjectSquareLaravel\Models\Todo;

class SettingsController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        $slack = app()->make('SettingManager')->getSettingByKey('SLACK_URL');

        return view('projectsquare::administration.settings.index', [
            'slack' => ($slack) ? $slack->value : null,
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('SettingManager')->createOrUpdateProjectSetting(
                null,
                $request->key,
                $request->value
            );
            $request->session()->flash('confirmation', trans('projectsquare::settings.update_setting_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::settings.update_setting_error'));
        }

        return redirect()->route('settings_index');
    }

    public function update_personal_settings(Request $request)
    {
        parent::__construct($request);

        try {
            app()->make('SettingManager')->createOrUpdateUserSetting(
                $this->getUser()->id,
                $request->key,
                $request->value
            );
            $request->session()->flash('confirmation', trans('projectsquare::settings.update_setting_success'));
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::settings.update_setting_error'));
        }

        return redirect()->route('settings_index');
    }

    public function reset_platform(Request $request)
    {
        parent::__construct($request);

        if ($this->isUserAnAdmin()) {
            TicketState::truncate();
            Ticket::truncate();
            Task::truncate();
            Message::truncate();
            Conversation::truncate();
            Client::truncate();
            Todo::truncate();
            Project::truncate();
            Phase::truncate();
            Event::truncate();
            Notification::truncate();

            $users = app()->make('UserManager')->getUsers();
            foreach ($users as $user) {
                if ($user->id != $this->getUser()->id) {
                    $user->projects()->detach();
                    $user->delete();
                } else {
                    $user->projects()->detach();
                }
            }

            $request->session()->flash('confirmation', trans('projectsquare::settings.reset_platform_success'));
        }

        return redirect()->route('settings_index');
    }
}