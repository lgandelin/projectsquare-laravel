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
        $eventModel = (!isset($event->id)) ? new Event() : Event::find($event->id);
        $eventModel->name = $event->name;
        $eventModel->start_time = $event->startTime->format('Y-m-d H:i:s');
        $eventModel->end_time = $event->endTime->format('Y-m-d H:i:s');
        $eventModel->user_id = $event->userID;
        $eventModel->ticket_id = $event->ticketID;
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
        $event->ticketID = $eventModel->tickect_id;
        $event->projectID = $eventModel->project_id;

        return $event;
    }
}