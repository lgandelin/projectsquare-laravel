<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Utility;

use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class MonitoringController extends BaseController
{
    public function index()
    {
        return view('projectsquare::monitoring.index', [
            'alerts' => app()->make('AlertManager')->getAlertsPaginatedList(),
        ]);
    }
}
