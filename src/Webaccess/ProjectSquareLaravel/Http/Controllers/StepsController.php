<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Webaccess\ProjectSquare\Decorators\StepDecorator;
use Webaccess\ProjectSquare\Requests\Steps\CreateStepRequest;
use Webaccess\ProjectSquare\Requests\Steps\DeleteStepRequest;
use Webaccess\ProjectSquare\Requests\Steps\GetStepRequest;
use Webaccess\ProjectSquare\Requests\Steps\UpdateStepRequest;

class StepsController extends BaseController
{
    public function get_step()
    {
        try {
            $step = app()->make('GetStepInteractor')->execute(new GetStepRequest([
                'stepID' => Input::get('id'),
            ]));

            return response()->json([
                'step' => (new StepDecorator())->decorate($step),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
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

            return response()->json([
                'message' => trans('projectsquare::steps.create_step_success'),
                'step' => (new StepDecorator())->decorate($response->step),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
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

            return response()->json([
                'message' => trans('projectsquare::steps.edit_step_success'),
                'step' => (new StepDecorator())->decorate($response->step),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete()
    {
        try {
            app()->make('DeleteStepInteractor')->execute(new DeleteStepRequest([
                'stepID' => Input::get('step_id'),
                'requesterUserID' => $this->getUser()->id,
            ]));

            return response()->json([
                'message' => trans('projectsquare::steps.delete_step_success'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
