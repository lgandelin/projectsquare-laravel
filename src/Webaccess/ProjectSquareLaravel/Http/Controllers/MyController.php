<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Tools\UploadTool;

class MyController extends BaseController
{
    public function index()
    {
        return view('projectsquare::my.index', [
            'user' => $this->getUser(),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function udpate_profile()
    {
        $userID = $this->getUser()->id;
        try {
            app()->make('UserManager')->updateUser(
                $userID,
                Input::get('first_name'),
                Input::get('last_name'),
                Input::get('email'),
                Input::get('password')
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::my.edit_profile_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::my.edit_profile_error'));
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
}