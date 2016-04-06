<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Requests\Planning\CreateStepRequest;
use Webaccess\ProjectSquare\Requests\Planning\DeleteStepRequest;
use Webaccess\ProjectSquare\Requests\Planning\GetStepRequest;
use Webaccess\ProjectSquare\Requests\Planning\UpdateStepRequest;

class PlanningController extends BaseController
{
    public function get_step()
    {
        try {
            $step = app()->make('GetStepInteractor')->execute(new GetStepRequest([
                'stepID' => Input::get('id')
            ]));
            $step->start_time = $step->startTime->format(DATE_ISO8601);
            $step->end_time = $step->endTime->format(DATE_ISO8601);
            $step->project_id = $step->projectID;

            return response()->json(['step' => $step], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        try {
            $response = app()->make('CreateStepInteractor')->execute(new CreateStepRequest([
                'name' => Input::get('name'),
                'projectID' => Input::get('project_id'),
                'startTime' => new \DateTime(Input::get('start_time')),
                'endTime' => new \DateTime(Input::get('end_time')),
                'color' => Input::get('color'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $step = $response->step;
            $step->start_time = $step->startTime->format(DATE_ISO8601);
            $step->end_time = $step->endTime->format(DATE_ISO8601);

            return response()->json(['message' => trans('projectsquare::steps.create_step_success'), 'step' => $step], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update()
    {
        try {
            $response = app()->make('UpdateStepInteractor')->execute(new UpdateStepRequest([
                'stepID' => Input::get('step_id'),
                'name' => Input::get('name'),
                'startTime' => new \DateTime(Input::get('start_time')),
                'endTime' => new \DateTime(Input::get('end_time')),
                'projectID' => Input::get('project_id'),
                'color' => Input::get('color'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            $step = $response->step;
            $step->start_time = $step->startTime->format(DATE_ISO8601);
            $step->end_time = $step->endTime->format(DATE_ISO8601);
            
            return response()->json(['message' => trans('projectsquare::steps.edit_step_success'), 'step' => $step], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete()
    {
        try {
            app()->make('DeleteStepInteractor')->execute(new DeleteStepRequest([
                'stepID' => Input::get('step_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            return response()->json(['message' => trans('projectsquare::steps.delete_step_success')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
