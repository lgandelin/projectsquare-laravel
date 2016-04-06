<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Step as StepEntity;
use Webaccess\ProjectSquare\Repositories\StepRepository;
use Webaccess\ProjectSquareLaravel\Models\Step;

class EloquentStepRepository implements StepRepository
{
    public function getStep($stepID)
    {
        $stepModel = $this->getStepModel($stepID);

        return $this->getStepEntity($stepModel);
    }

    public function getStepModel($stepID)
    {
        return Step::find($stepID);
    }

    public function getSteps($projectID)
    {
        $steps = [];
        $stepsModel = Step::where('project_id', '=', $projectID);
        foreach ($stepsModel->get() as $stepModel) {
            $step = $this->getStepEntity($stepModel);
            $steps[]= $step;
        }

        return $steps;
    }

    public function persistStep(StepEntity $step)
    {
        $stepModel = (!isset($step->id)) ? new Step() : Step::find($step->id);
        $stepModel->name = $step->name;
        $stepModel->project_id = $step->projectID;
        $stepModel->start_time = $step->startTime->format('Y-m-d H:i:s');
        $stepModel->end_time = $step->endTime->format('Y-m-d H:i:s');

        $stepModel->save();

        $step->id = $stepModel->id;

        return $step;
    }

    public function removeStep($stepID)
    {
        $step = $this->getStepModel($stepID);
        $step->delete();
    }

    private function getStepEntity($stepModel)
    {
        $step = new StepEntity();
        $step->id = $stepModel->id;
        $step->name = $stepModel->name;
        $step->projectID = $stepModel->project_id;
        $step->startTime = new \DateTime($stepModel->start_time);
        $step->endTime = new \DateTime($stepModel->end_time);

        return $step;
    }
}