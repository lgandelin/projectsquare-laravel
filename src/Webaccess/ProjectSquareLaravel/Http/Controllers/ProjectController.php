<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Interactors\Tickets\GetTicketInteractor;
use Webaccess\ProjectSquare\Requests\Steps\GetStepsRequest;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentTicketRepository;
use Webaccess\ProjectSquareLaravel\Tools\UploadTool;

class ProjectController extends BaseController
{
    public function index($projectID)
    {
        return view('projectsquare::project.index', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'tickets' => (new GetTicketInteractor(new EloquentTicketRepository()))->getTicketsPaginatedList($this->getUser()->id, env('TICKETS_PER_PAGE', 10), $projectID),
        ]);
    }

    public function cms($projectID)
    {
        return view('projectsquare::project.cms', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
        ]);
    }

    public function tickets($projectID)
    {
        return view('projectsquare::project.tickets', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'tickets' => (new GetTicketInteractor(new EloquentTicketRepository()))->getTicketsPaginatedList($this->getUser()->id, env('TICKETS_PER_PAGE', 10), $projectID),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function monitoring($projectID)
    {
        $requests = app()->make('RequestManager')->getRequestsByProject($projectID)->get();

        return view('projectsquare::project.monitoring', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'average_loading_time' => app()->make('RequestManager')->getAverageLoadingTime($requests),
            'availability_percentage' => (count($requests) > 0) ? app()->make('RequestManager')->getAvailabilityPercentage($projectID) : null,
            'status_codes' => (count($requests) > 0) ? app()->make('RequestManager')->getStatusCodes($projectID) : null,
            'loading_times' => (count($requests) > 0) ? app()->make('RequestManager')->getLoadingTimes($projectID) : null,
            'max_loading_time' => app()->make('RequestManager')->getMaxLoadingTimeByProject($projectID),
            'requests' => app()->make('RequestManager')->formatDataForGraphs($requests),
        ]);
    }

    public function settings($projectID)
    {
        $settingAcceptableLoadingTime = app()->make('SettingManager')->getSettingByKeyAndProject('ACCEPTABLE_LOADING_TIME', $projectID);
        $settingAlertLoadingTimeEmail = app()->make('SettingManager')->getSettingByKeyAndProject('ALERT_LOADING_TIME_EMAIL', $projectID);
        $settingSlackChannel = app()->make('SettingManager')->getSettingByKeyAndProject('SLACK_CHANNEL', $projectID);
        $gaViewID = app()->make('SettingManager')->getSettingByKeyAndProject('GA_VIEW_ID', $projectID);

        return view('projectsquare::project.settings', [
            'acceptable_loading_time' => ($settingAcceptableLoadingTime) ? $settingAcceptableLoadingTime->value : null,
            'alert_loading_time_email' => ($settingAlertLoadingTimeEmail) ? $settingAlertLoadingTimeEmail->value : null,
            'slack_channel' => ($settingSlackChannel) ? $settingSlackChannel->value : null,
            'ga_view_id' => ($gaViewID) ? $gaViewID->value : null,
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function update_settings()
    {
        try {
            app()->make('SettingManager')->createOrUpdateSetting(
                Input::get('project_id'),
                Input::get('key'),
                Input::get('value')
            );
            $this->request->session()->flash('confirmation', trans('projectsquare::settings.update_setting_success'));
        } catch (\Exception $e) {
            $this->request->session()->flash('error', trans('projectsquare::settings.update_setting_error'));
        }

        return redirect()->route('project_settings', ['id' => Input::get('project_id')]);
    }

    public function messages($projectID)
    {
        return view('projectsquare::project.messages', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'conversations' => app()->make('ConversationManager')->getConversationsByProject($projectID),
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'confirmation' => ($this->request->session()->has('confirmation')) ? $this->request->session()->get('confirmation') : null,
        ]);
    }

    public function calendar($projectID)
    {
        return view('projectsquare::project.calendar', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'steps' => app()->make('GetStepsInteractor')->execute(new GetStepsRequest([
                'projectID' => $projectID,
            ])),
        ]);
    }

    public function seo($projectID)
    {
        $gaViewID = app()->make('SettingManager')->getSettingByKeyAndProject('GA_VIEW_ID', $projectID);

        return view('projectsquare::project.seo', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'gaViewID' => ($gaViewID) ? $gaViewID->value : null,
            'startDate' => (new Carbon())->addDay(-30)->format('d/m/Y'),
            'endDate' => (new Carbon())->format('d/m/Y'),
        ]);
    }

    public function files($projectID)
    {
        return view('projectsquare::project.files', [
            'project' => app()->make('ProjectManager')->getProject($projectID),
            'files' => app()->make('FileManager')->getFilesByProject($projectID),
        ]);
    }

    public function upload_file()
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

                $data->deleteUrl = action('\Webaccess\ProjectSquareLaravel\Http\Controllers\ProjectController@delete_file', ['id' => $fileID]);
                $data->deleteType = 'GET';
            }

            return response()->json(['files' => [$data]], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete_file($fileID)
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
