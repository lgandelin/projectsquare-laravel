<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Routing\Controller;

class APIController extends Controller
{
    public function users_count()
    {
        return response()->json([
            'count' => sizeof(app()->make('UserManager')->getAgencyUsers())
        ]);
    }
}