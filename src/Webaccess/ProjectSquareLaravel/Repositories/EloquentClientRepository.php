<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquareLaravel\Models\Client;
use Webaccess\ProjectSquare\Repositories\ClientRepository;

class EloquentClientRepository implements ClientRepository
{
    public static function getClient($clientID)
    {
        return Client::find($clientID);
    }

    public static function getClients()
    {
        return Client::all();
    }

    public static function getClientsPaginatedList($limit = null)
    {
        return Client::orderBy('updated_at', 'DESC')->paginate($limit);
    }

    public static function createClient($name, $address)
    {
        $client = new Client();
        $client->save();
        self::updateClient($client->id, $name, $address);
    }

    public static function updateClient($clientID, $name, $address)
    {
        $client = self::getClient($clientID);
        $client->name = $name;
        $client->address = $address;
        $client->save();
    }

    public static function deleteClient($clientID)
    {
        $client = self::getClient($clientID);
        $client->delete();
    }
}
