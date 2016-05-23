<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Project;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;
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
}