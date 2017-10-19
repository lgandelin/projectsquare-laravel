<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use DateTime;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Cache;
use Webaccess\ProjectSquare\Entities\Phase as PhaseEntity;
use Webaccess\ProjectSquare\Repositories\PhaseRepository;
use Webaccess\ProjectSquareLaravel\Models\Phase;

class EloquentPhaseRepository implements PhaseRepository
{
    public function getPhase($phaseID)
    {
        if ($phaseModel = $this->getPhaseModel($phaseID)) {
            return $this->getPhaseEntity($phaseModel);
        }

        return false;
    }

    public function getPhaseModel($phaseID)
    {
        return Phase::find($phaseID);
    }

    public function getPhases($projectID)
    {
        $phases = [];

        $phasesModel = Cache::remember('phases#' . $projectID, 5, function() use ($projectID) {
            return Phase::where('project_id', '=', $projectID)->orderBy('order', 'asc')->get();
        });

        foreach ($phasesModel as $phaseModel) {
            $phase = $this->getPhaseEntity($phaseModel);
            $phases[] = $phase;
        }

        return $phases;
    }

    public function persistPhase(PhaseEntity $phase)
    {
        if (!isset($phase->id)) {
            $phaseModel = new Phase();
            $phaseID = Uuid::uuid4()->toString();
            $phaseModel->id = $phaseID;
            $phase->id = $phaseID;
        } else {
            $phaseModel = Phase::find($phase->id);
        }
        
        $phaseModel->name = $phase->name;
        $phaseModel->order = $phase->order;
        if ($phase->dueDate) $phaseModel->due_date = $phase->dueDate->format('Y-m-d H:i:s');
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
