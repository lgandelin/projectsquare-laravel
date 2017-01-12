<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webaccess\ProjectSquareLaravel\Models\Platform;

class APIController extends Controller
{
    public function users_count()
    {
        return response()->json([
            'count' => sizeof(app()->make('UserManager')->getAgencyUsers())
        ]);
    }

    public function update_users_count(Request $request)
    {
        if ($request->token != env('API_TOKEN')) {
            return response()->json([
                'success' => false,
                'error' => -1,
            ]);
        }

        if ($request->count > 0) {
            $platform = Platform::first();
            $platform->users_limit = $request->count;
            $platform->save();

            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => -2,
        ]);
    }
}