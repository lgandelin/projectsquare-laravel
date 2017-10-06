<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Event as EventEntity;
use Webaccess\ProjectSquare\Repositories\EventRepository;
use Webaccess\ProjectSquareLaravel\Models\Event;

class EloquentEventRepository implements EventRepository
{
    public function getEvent($eventID)
    {
        if ($eventModel = $this->getEventModel($eventID)) {
            return $this->getEventEntity($eventModel);
        }

        return false;
    }

    public function getEventModel($eventID)
    {
        return Event::find($eventID);
    }

    public function getEvents($userID = null, $projectID = null, $ticketID = null, $taskID = null)
    {
        $events = [];
        $eventsModel = Event::with('project', 'project.client');

        if ($userID) {
            $eventsModel->where('user_id', '=', $userID);
        }
        if ($projectID) {
            $eventsModel->where('project_id', '=', $projectID);
        }
        if ($ticketID) {
            $eventsModel->where('ticket_id', '=', $ticketID);
        }
        if ($taskID) {
            $eventsModel->where('task_id', '=', $taskID);
        }
        foreach ($eventsModel->get() as $eventModel) {
            $event = $this->getEventEntity($eventModel);

            //Event duration
            $durationInMinutes = 8 * 60;
            if ($event->endTime->format('Y-m-d') == $event->startTime->format('Y-m-d')) {
                $startTimeOfDay = clone $event->endTime;
                $startTimeOfDay->setTime(9, 0, 0);
                $interval = $event->endTime->diff($event->startTime);
                $durationInMinutes = ($interval->h * 60 + $interval->i);
            }
            $event->durationInHours = $durationInMinutes/60;
            if ($event->durationInHours > 8) {
                $event->durationInHours = 8;
            }
            $events[] = $event;
        }

        return $events;
    }

    public function persistEvent(EventEntity $event)
    {
        $eventModel = (!isset($event->id)) ? new Event() : Event::find($event->id);
        $eventModel->name = $event->name;
        $eventModel->start_time = $event->startTime->format('Y-m-d H:i:s');
        $eventModel->end_time = $event->endTime->format('Y-m-d H:i:s');
        $eventModel->user_id = $event->userID;
        $eventModel->ticket_id = $event->ticketID;
        $eventModel->task_id = $event->taskID;
        $eventModel->project_id = $event->projectID;

        $eventModel->save();

        $event->id = $eventModel->id;

        return $event;
    }

    public function removeEvent($eventID)
    {
        $event = $this->getEventModel($eventID);
        $event->delete();
    }

    private function getEventEntity($eventModel)
    {
        $event = new EventEntity();
        $event->id = $eventModel->id;
        $event->name = $eventModel->name;
        $event->startTime = new \DateTime($eventModel->start_time);
        $event->endTime = new \DateTime($eventModel->end_time);
        $event->userID = $eventModel->user_id;
        $event->ticketID = $eventModel->ticket_id;
        $event->taskID = $eventModel->task_id;
        $event->projectID = $eventModel->project_id;

        if ($eventModel->project) {
            $event->color = $eventModel->project->color;
            $event->projectName = $eventModel->project->name;
            $event->projectClient = isset($eventModel->project->client) ? $eventModel->project->client->name : null;
        }

        return $event;
    }
}
