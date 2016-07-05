<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentClientRepository;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentProjectRepository;

class ClientManager
{
    public static function getClient($clientID)
    {
        if (!$client = EloquentClientRepository::getClient($clientID)) {
            throw new \Exception(trans('projectsquare::clients.client_not_found'));
        }

        return $client;
    }

    public static function getClients()
    {
        return EloquentClientRepository::getClients();
    }

    public static function getClientsPaginatedList()
    {
        return EloquentClientRepository::getClientsPaginatedList(env('CLIENTS_PER_PAGE', 10));
    }

    public static function createClient($name, $address)
    {
        return EloquentClientRepository::createClient($name, $address);
    }

    public static function updateClient($clientID, $name, $address)
    {
        EloquentClientRepository::updateClient($clientID, $name, $address);
    }

    public static function deleteClient($clientID)
    {
        (new ProjectManager())->deleteProjectByClient($clientID);
        EloquentClientRepository::deleteClient($clientID);
    }
}
