<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Step as StepEntity;
use Webaccess\ProjectSquare\Repositories\StepRepository;
use Webaccess\ProjectSquareLaravel\Models\Step;

class EloquentStepRepository implements StepRepository
{
    public function getStep($eventID)
    {
        $eventModel = $this->getStepModel($eventID);

        return $this->getStepEntity($eventModel);
    }

    public function getStepModel($eventID)
    {
        return Step::find($eventID);
    }

    public function getSteps($projectID)
    {
        $events = [];
        $eventsModel = Step::where('project_id', '=', $projectID);
        foreach ($eventsModel->get() as $eventModel) {
            $event = $this->getStepEntity($eventModel);
            $events[]= $event;
        }

        return $events;
    }

    public function persistStep(StepEntity $event)
    {
        $eventModel = (!isset($event->id)) ? new Step() : Step::find($event->id);
        $eventModel->name = $event->name;
        $eventModel->project_id = $event->projectID;
        $eventModel->start_time = $event->startTime->format('Y-m-d H:i:s');
        $eventModel->end_time = $event->endTime->format('Y-m-d H:i:s');

        $eventModel->save();

        $event->id = $eventModel->id;

        return $event;
    }

    public function removeStep($eventID)
    {
        $event = $this->getStepModel($eventID);
        $event->delete();
    }

    private function getStepEntity($eventModel)
    {
        $event = new StepEntity();
        $event->id = $eventModel->id;
        $event->name = $eventModel->name;
        $event->projectID = $eventModel->project_id;
        $event->startTime = new \DateTime($eventModel->start_time);
        $event->endTime = new \DateTime($eventModel->end_time);

        return $event;
    }
}