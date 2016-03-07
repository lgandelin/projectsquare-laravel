<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers\Agency;

use Webaccess\ProjectSquareLaravel\Http\Controllers\BaseController;

class AgencyController extends BaseController
{
    public function index()
    {
        return view('projectsquare::agency.index', [
        ]);
    }
}
