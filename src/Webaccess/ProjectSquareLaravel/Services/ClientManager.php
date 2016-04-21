<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentClientRepository;

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

    public static function createClient($name)
    {
        EloquentClientRepository::createClient($name);
    }

    public static function updateClient($clientID, $name)
    {
        EloquentClientRepository::updateClient($clientID, $name);
    }

    public static function deleteClient($clientID)
    {
        EloquentClientRepository::deleteClient($clientID);
    }
}