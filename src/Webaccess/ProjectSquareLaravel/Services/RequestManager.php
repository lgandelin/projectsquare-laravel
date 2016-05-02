<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentRequestRepository;

class RequestManager
{
    public static function createRequest($projectID, $statusCode, $loadingTime)
    {
        EloquentRequestRepository::createRequest($projectID, $statusCode, $loadingTime);
    }

    public static function getRequestsByProject($projectID)
    {
        return EloquentRequestRepository::getRequestsByProject($projectID);
    }

    public static function getAvailabilityPercentage($projectID)
    {
        $requests = self::getRequestsByProject($projectID);
        $totalRequests = count($requests->get());

        return 100 * EloquentRequestRepository::getStatusCodesCount($projectID, '20') / $totalRequests;
    }

    public static function getStatusCodes($projectID)
    {
        $requests = self::getRequestsByProject($projectID);
        $totalRequests = count($requests->get());

        return [
            '20x' => 100 * EloquentRequestRepository::getStatusCodesCount($projectID, '20') / $totalRequests,
            '40x' => 100 * EloquentRequestRepository::getStatusCodesCount($projectID, '40') / $totalRequests,
            '50x' => 100 * EloquentRequestRepository::getStatusCodesCount($projectID, '50') / $totalRequests,
        ];
    }

    public static function getLoadingTimes($projectID)
    {
        $requests = self::getRequestsByProject($projectID);
        $totalRequests = count($requests->get());

        return [
            'below1' => 100 * EloquentRequestRepository::getLoadingTimesCount($projectID, 1) / $totalRequests,
            'between1and1.5' => 100 * EloquentRequestRepository::getLoadingTimesCount($projectID, 1.5, 1) / $totalRequests,
            'between1.5and3' => 100 * EloquentRequestRepository::getLoadingTimesCount($projectID, 3, 1.5) / $totalRequests,
            'morethan3' => EloquentRequestRepository::getLoadingTimesCount($projectID, null, 3) / $totalRequests,
        ];
    }

    public static function getLastRequestByProject($projectID)
    {
        return EloquentRequestRepository::getLastRequestByProject($projectID);
    }

    public function getMaxLoadingTimeByProject($projectID)
    {
        return EloquentRequestRepository::getMaxLoadingTimeByProject($projectID);
    }

    public static function formatDataForGraphs($requests)
    {
        $data = [];
        foreach ($requests as $request) {
            $data[] = [strtotime($request->created_at) * 1000, $request->loading_time];
        }

        return $data;
    }

    public static function getAverageLoadingTime($requests)
    {
        $averageLoadingTime = 0;
        foreach ($requests as $request) {
            $averageLoadingTime += $request->loading_time;
        }

        if (count($requests) > 0) {
            $averageLoadingTime /= count($requests);
        }

        return $averageLoadingTime;
    }

    public static function deleteOldRequests()
    {
        EloquentRequestRepository::deleteOldRequests();
    }
}
