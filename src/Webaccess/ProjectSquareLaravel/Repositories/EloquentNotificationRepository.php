<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\Notification as NotificationEntity;
use Webaccess\ProjectSquare\Repositories\NotificationRepository;
use Webaccess\ProjectSquareLaravel\Models\Notification;

class EloquentNotificationRepository implements NotificationRepository
{
    public function getNotification($notificationID)
    {
        $notificationModel = $this->getNotificationModel($notificationID);

        return $this->getNotificationEntity($notificationModel);
    }

    public function getNotificationModel($notificationID)
    {
        return Notification::find($notificationID);
    }

    public function getNotifications($userID)
    {
        $notifications = [];
        $notificationsModel = Notification::where('user_id', '=', $userID);
        foreach ($notificationsModel->get() as $notificationModel) {
            $notifications[]= $this->getNotificationEntity($notificationModel);
        }

        return $notifications;
    }

    public function getUnreadNotifications($userID)
    {
        $notifications = [];
        $notificationsModel = Notification::where('user_id', '=', $userID)->where('read', '=', 0);
        foreach ($notificationsModel->get() as $notificationModel) {
            $notifications[]= $this->getNotificationEntity($notificationModel);
        }

        return $notifications;
    }

    public function persistNotification(NotificationEntity $notification)
    {
        $notificationModel = (!isset($notification->id)) ? new Notification() : Notification::find($notification->id);
        $notificationModel->type = $notification->type;
        $notificationModel->read = $notification->read;
        $notificationModel->user_id = $notification->userID;
        $notificationModel->entity_id = $notification->entityID;

        $notificationModel->save();

        $notification->id = $notificationModel->id;

        return $notification;
    }

    public function removeNotification($notificationID)
    {
        $notification = $this->getNotificationModel($notificationID);
        $notification->delete();
    }

    private function getNotificationEntity($notificationModel)
    {
        $notification = new NotificationEntity();
        $notification->id = $notificationModel->id;
        $notification->type = $notificationModel->type;
        $notification->read = $notificationModel->read;
        $notification->userID = $notificationModel->user_id;
        $notification->entityID = $notificationModel->entity_id;

        return $notification;
    }
}