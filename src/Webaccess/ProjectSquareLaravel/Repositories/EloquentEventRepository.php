<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Event as EventEntity;
use Webaccess\ProjectSquare\Repositories\EventRepository;
use Webaccess\ProjectSquareLaravel\Models\Event;

class EloquentEventRepository implements EventRepository
{
    public function getEvent($eventID)
    {
        $eventModel = $this->getEventModel($eventID);

        return $this->getEventEntity($eventModel);
    }

    public function getEventModel($eventID)
    {
        return Event::find($eventID);
    }

    public function getEvents()
    {
        // TODO: Implement getEvents() method.
    }

    public function getEventsByUser($userID)
    {
        $events = [];
        $eventsModel = Event::where('user_id', '=', $userID)->get();
        foreach ($eventsModel as $eventModel) {
            $events[]= $this->getEventEntity($eventModel);
        }

        return $events;
    }

    public function persistEvent(EventEntity $event)
    {
        // TODO: Implement persistEvent() method.
    }

    public function removeEvent($eventID)
    {
        // TODO: Implement removeEvent() method.
    }

    private function getEventEntity($eventModel)
    {
        $event = new EventEntity();
        $event->id = $eventModel->id;
        $event->name = $eventModel->name;
        $event->startTime = new \DateTime($eventModel->start_time);
        $event->endTime = new \DateTime($eventModel->end_time);
        $event->userID = $eventModel->user_id;
        $event->ticketID = $eventModel->tickect_id;
        $event->projectID = $eventModel->project_id;

        return $event;
    }
}