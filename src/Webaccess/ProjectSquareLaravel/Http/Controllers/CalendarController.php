<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

class CalendarController extends BaseController
{
    public function index()
    {
        return view('projectsquare::calendar.index', [
        ]);
    }
}
