<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

class MonitoringController extends BaseController
{
    public function index()
    {
        return view('projectsquare::monitoring.index', [
            'alerts' => app()->make('AlertManager')->getAlertsPaginatedList(),
        ]);
    }
}
