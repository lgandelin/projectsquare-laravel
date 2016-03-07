<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Illuminate\Support\Facades\Hash;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentUserRepository;

class UserManager
{
    public function __construct()
    {
        $this->repository = new EloquentUserRepository();    
    }
    
    public function getUsersPaginatedList()
    {
        return $this->repository->getUsersPaginatedList(env('USERS_PER_PAGE', 10));
    }

    public function getAgencyUsers()
    {
        return $this->repository->getAgencyUsers();
    }

    public function getUsers()
    {
        return $this->repository->getUsers();
    }

    public function getUser($userID)
    {
        if (!$user = $this->repository->getUser($userID)) {
            throw new \Exception(trans('projectsquare::users.user_not_found'));
        }

        return $user;
    }

    public function createUser($firstName, $lastName, $email, $password, $clientID)
    {
        $this->repository->createUser($firstName, $lastName, $email, $password, $clientID);
    }

    public function updateUser($userID, $firstName, $lastName, $email, $password, $clientID)
    {
        $this->repository->updateUser($userID, $firstName, $lastName, $email, ($password) ? Hash::make($password) : null, $clientID);
    }

    public function deleteUser($userID)
    {
        $this->repository->deleteUser($userID);
    }
}
