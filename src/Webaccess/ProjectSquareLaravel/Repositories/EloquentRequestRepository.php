<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquareLaravel\Models\Request;

class EloquentRequestRepository
{
    public static function createRequest($projectID, $statusCode, $loadingTime)
    {
        $request = new Request();
        $request->project_id = $projectID;
        $request->status_code = $statusCode;
        $request->loading_time = $loadingTime;
        $request->save();
    }

    public static function getRequestsByProject($projectID)
    {
        $requests = Request::where('project_id', '=', $projectID);

        $yesterday = new \DateTime();
        $yesterday->sub(new \DateInterval('PT24H'));

        $requests->where('created_at', '>=', $yesterday);

        return $requests;
    }

    public static function getAvailabilityPercentage($projectID)
    {
        $requests = self::getRequestsByProject($projectID);
        $totalRequests = count($requests->get());

        return 100 * self::getStatusCodesCount($projectID, '20') / $totalRequests;
    }

    public static function getStatusCodesCount($projectID, $statusCode)
    {
        return self::getRequestsByProject($projectID)->where('status_code', 'like', '%'.$statusCode.'%')->count();
    }

    public static function getLoadingTimesCount($projectID, $upperLimit = null, $lowerLimit = null)
    {
        $requests = self::getRequestsByProject($projectID);

        if ($upperLimit) {
            $requests->where('loading_time', '<=', $upperLimit);
        }

        if ($lowerLimit) {
            $requests->where('loading_time', '>', $lowerLimit);
        }

        return $requests->count();
    }

    public static function getLastRequestByProject($projectID)
    {
        return Request::where('project_id', '=', $projectID)->orderBy('created_at', 'DESC')->first();
    }

    public static function getMaxLoadingTimeByProject($projectID)
    {
        return self::getRequestsByProject($projectID)->max('loading_time');
    }

    public static function deleteOldRequests()
    {
        $lastWeek = new \DateTime();
        $lastWeek->sub(new \DateInterval('P7D'));

        Request::where('created_at', '<=', $lastWeek)->delete();
    }
}
