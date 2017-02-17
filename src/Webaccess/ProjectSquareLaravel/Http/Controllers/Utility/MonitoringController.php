<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Utility;

use Illuminate\Http\Request;
use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class MonitoringController extends BaseController
{
    public function index(Request $request)
    {
        parent::__construct($request);

        return view('projectsquare::monitoring.index', [
            'alerts' => app()->make('AlertManager')->getAlertsPaginatedList(),
        ]);
    }
}
