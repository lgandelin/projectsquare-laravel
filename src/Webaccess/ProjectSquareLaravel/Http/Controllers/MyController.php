<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Tools\UploadTool;

class TwoPasswordsException extends \Exception {}

class MyController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);
        
        return view('projectsquare::my.index', [
            'user' => $this->getUser(),
            'email_notification_ticket_created' => app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TICKET_CREATED', $this->getUser()->id),
            'email_notification_ticket_updated' => app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TICKET_UPDATED', $this->getUser()->id),
            'email_notification_ticket_deleted' => app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TICKET_DELETED', $this->getUser()->id),
            'email_notification_task_created' => app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TASK_CREATED', $this->getUser()->id),
            'email_notification_task_updated' => app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TASK_UPDATED', $this->getUser()->id),
            'email_notification_task_deleted' => app()->make('SettingManager')->getSettingByKeyAndUser('EMAIL_NOTIFICATION_TASK_DELETED', $this->getUser()->id),
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function udpate_profile(Request $request)
    {
        parent::__construct($request);
        
        $userID = $this->getUser()->id;
        try {
            if (Input::get('password') != '' && Input::get('password') != Input::get('password_confirmation')) {
                throw new TwoPasswordsException();
            } else {
                app()->make('UserManager')->updateUser(
                    $userID,
                    Input::get('first_name'),
                    Input::get('last_name'),
                    Input::get('email'),
                    Input::get('password')
                );
                $request->session()->flash('confirmation', trans('projectsquare::my.edit_profile_success'));
            }
        } catch (TwoPasswordsException $e) {
            $request->session()->flash('error', 'Les deux mots de passe ne correspondent pas');
        } catch (\Exception $e) {
            $request->session()->flash('error', trans('projectsquare::my.edit_profile_error'));
        }

        return redirect()->route('my', ['id' => Input::get('user_id')]);
    }

    public function upload_avatar()
    {
        try {
            $data = UploadTool::uploadFileForUser(
                Input::file('files'),
                $this->getUser()->id
            );

            return response()->json(['files' => [$data]], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update_notifications(Request $request)
    {
        parent::__construct($request);

        try {
            $keys = [
                'EMAIL_NOTIFICATION_TASK_CREATED',
                'EMAIL_NOTIFICATION_TASK_UPDATED',
                'EMAIL_NOTIFICATION_TASK_DELETED',
                'EMAIL_NOTIFICATION_TICKET_CREATED',
                'EMAIL_NOTIFICATION_TICKET_UPDATED',
                'EMAIL_NOTIFICATION_TICKET_DELETED',
            ];

            foreach ($keys as $key) {
                app()->make('SettingManager')->createOrUpdateUserSetting(
                    $this->getUser()->id,
                    $key,
                    boolval(Input::get(strtolower($key)))
                );
            }
            $request->session()->flash('confirmation', trans('projectsquare::settings.update_setting_success'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return redirect()->route('my', ['id' => Input::get('user_id')]);
    }
}