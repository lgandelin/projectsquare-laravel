<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use GuzzleHttp\Client;

class GuzzlePaymentAPIService
{
    /**
     * @param $platformSlug
     * @param $usersCount
     */
    public static function updateUsersCount($platformSlug, $usersCount)
    {
        $client = new Client(['base_uri' => env('API_PAYMENT_ENDPOINT')]);
        $response = $client->post('/update_users_count', [
            'json' => [
                'platform_slug' => $platformSlug,
                'users_count' => $usersCount,
                'api_token' => env('API_TOKEN')
            ]
        ]);
    }
}