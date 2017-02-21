<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use DateTime;
use Webaccess\ProjectSquare\Entities\Phase as PhaseEntity;
use Webaccess\ProjectSquare\Repositories\PhaseRepository;
use Webaccess\ProjectSquareLaravel\Models\Phase;

class EloquentPhaseRepository implements PhaseRepository
{
    public function getPhase($phaseID)
    {
        $phaseModel = $this->getPhaseModel($phaseID);

        return $this->getPhaseEntity($phaseModel);
    }

    public function getPhaseModel($phaseID)
    {
        return Phase::find($phaseID);
    }

    public function getPhases($projectID)
    {
        $phases = [];
        $phasesModel = Phase::where('project_id', '=', $projectID)->orderBy('order', 'asc');

        foreach ($phasesModel->get() as $phaseModel) {
            $phase = $this->getPhaseEntity($phaseModel);
            $phases[] = $phase;
        }

        return $phases;
    }

    public function persistPhase(PhaseEntity $phase)
    {
        $phaseModel = (!isset($phase->id)) ? new Phase() : Phase::find($phase->id);
        $phaseModel->name = $phase->name;
        $phaseModel->order = $phase->order;
        $phaseModel->due_date = $phase->dueDate->format('Y-m-d H:i:s');
        $phaseModel->project_id = $phase->projectID;

        $phaseModel->save();

        $phase->id = $phaseModel->id;

        return $phase;
    }

    public function removePhase($phaseID)
    {
        $phase = $this->getPhaseModel($phaseID);
        $phase->delete();
    }

    private function getPhaseEntity($phaseModel)
    {
        $phase = new PhaseEntity();
        $phase->id = $phaseModel->id;
        $phase->name = $phaseModel->name;
        $phase->order = $phaseModel->order;
        $phase->dueDate = new DateTime($phaseModel->due_date);
        $phase->projectID = $phaseModel->project_id;

        return $phase;
    }
}
