<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Project;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Notifications\CreateNotificationRequest;
use Webaccess\ProjectSquare\Interactors\Notifications\CreateNotificationInteractor;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentNotificationRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentUserRepository;
use Webaccess\ProjectSquareLaravel\Tools\UploadTool;

class FilesController extends BaseController
{
    public function index($projectID)
    {
        return view('projectsquare::project.files', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'files' => app()->make('FileManager')->getFilesByProject($projectID),
        ]);
    }

    public function upload()
    {
        try {
            if ($data = UploadTool::uploadFileForProject(
                Input::file('files'),
                Input::get('project_id')
            )) {
                $fileID = app()->make('FileManager')->createFile(
                    $data->name,
                    $data->path,
                    $data->thumbnailPath,
                    $data->mimeType,
                    $data->size,
                    null,
                    Input::get('project_id')
                );

                $data->deleteUrl = action('\Webaccess\ProjectSquareLaravel\Http\Controllers\Project\FilesController@delete', ['id' => $fileID]);
                $data->deleteType = 'GET';

                $this->createNotifications($this->getUser()->id, Input::get('project_id'), $fileID);
            }

            return response()->json(['files' => [$data]], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($fileID)
    {
        try {
            app()->make('FileManager')->deleteFile($fileID);
            $this->request->session()->flash('confirmation', trans('projectsquare::files.delete_file_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::files.delete_file_error'));
        }

        return redirect()->back();
    }

    private function createNotifications($userID, $projectID, $fileID)
    {
        $project = (new EloquentProjectRepository())->getProject($projectID);

        //Agency users
        foreach ((new EloquentUserRepository())->getUsersByProject($projectID) as $user) {
            if ($user->id != $userID) {
                $this->notifyUserIfRequired($user, $fileID);
            }
        }

        //Client users
        foreach ((new EloquentUserRepository())->getClientUsers($project->clientID) as $user) {
            if ($user->id != $userID) {
                $this->notifyUserIfRequired($user, $fileID);
            }
        }
    }

    private function notifyUserIfRequired($user, $fileID)
    {
        (new CreateNotificationInteractor(new EloquentNotificationRepository()))->execute(new CreateNotificationRequest([
            'userID' => $user->id,
            'entityID' => $fileID,
            'type' => 'FILE_UPLOADED',
        ]));
    }
}