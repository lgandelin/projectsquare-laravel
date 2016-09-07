<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquareLaravel\Models\Client;
use Webaccess\ProjectSquare\Entities\Client as ClientEntity;
use Webaccess\ProjectSquare\Repositories\ClientRepository;

class EloquentClientRepository implements ClientRepository
{
    public function getClient($clientID)
    {
        $clientModel = $this->getClientModel($clientID);

        return $this->getClientEntity($clientModel);
    }
    
    public function getClientModel($clientID)
    {
        return Client::find($clientID);
    }

    public function getClients()
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

        return $client->id;
    }

    public function persistClient(ClientEntity $client)
    {
        $clientModel = (!isset($client->id)) ? new Client() : Client::find($client->id);
        $clientModel->name = $client->name;
        $clientModel->address = $client->address;
        $clientModel->save();

        $client->id = $clientModel->id;

        return $client;
    }

    public static function updateClient($clientID, $name, $address)
    {
        $client = self::getClientModel($clientID);
        $client->name = $name;
        $client->address = $address;
        $client->save();
    }

    public function deleteClient($clientID)
    {
        $client = self::getClientModel($clientID);
        $client->delete();
    }

    private function getClientEntity($clientModel)
    {
        if (!$clientModel) {
            return false;
        }

        $client = new ClientEntity();
        $client->id = $clientModel->id;
        $client->name = $clientModel->name;
        $client->address = $clientModel->address;

        return $client;
    }
}
