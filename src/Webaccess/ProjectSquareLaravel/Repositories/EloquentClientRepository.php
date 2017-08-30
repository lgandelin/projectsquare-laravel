<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Ramsey\Uuid\Uuid;
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

    public static function getClientsPaginatedList($limit, $sortColumn = null, $sortOrder = null)
    {
        return Client::orderBy($sortColumn ? $sortColumn : 'updated_at', $sortOrder ? $sortOrder : 'DESC')->paginate($limit);
    }

    public static function createClient($name, $address)
    {
        $client = new Client();
        $client->id = Uuid::uuid4()->toString();
        $client->save();
        self::updateClient($client->id, $name, $address);

        return $client->id;
    }

    public function persistClient(ClientEntity $client)
    {
        if (!isset($client->id)) {
            $clientModel = new Client();
            $clientID = Uuid::uuid4()->toString();
            $clientModel->id = $clientID;
            $client->id = $clientID;
        } else {
            $clientModel = Client::find($client->id);
        }
        $clientModel->name = $client->name;
        $clientModel->address = $client->address;
        $clientModel->save();

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
