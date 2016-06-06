<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

class MyController extends BaseController
{
    public function index()
    {
        return view('projectsquare::my.index', [
            'user' => $this->getUser()
        ]);
    }
}