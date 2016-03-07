<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\User as UserEntity;
use Webaccess\ProjectSquareLaravel\Models\User;
use Webaccess\ProjectSquare\Repositories\UserRepository;

class EloquentUserRepository implements UserRepository
{
    public function getUsersPaginatedList($limit)
    {
        return User::with('client')->paginate($limit);
    }

    public function getAgencyUsers()
    {
        return User::whereNull('client_id')->get();
    }

    public function getUsers()
    {
        return User::all();
    }

    public function getUser($userID)
    {
        if ($userModel = User::find($userID)) {
            $user = new UserEntity;
            $user->id = $userModel->id;
            $user->email = $userModel->email;
            $user->firstName = $userModel->first_name;
            $user->lastName = $userModel->last_name;
            $user->password = $userModel->password;
            //TODO : client_id

            return $user;
        }

        return false;
    }

    public function createUser($firstName, $lastName, $email, $password, $clientID)
    {
        $user = new User();
        $user->save();
        self::updateUser($user->id, $firstName, $lastName, $email, $password, $clientID);
    }

    public function updateUser($userID, $firstName, $lastName, $email, $password, $clientID)
    {
        $user = self::getUser($userID);
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        if ($password) {
            $user->password = $password;
        }
        $user->client_id = $clientID;
        $user->save();
    }

    public function deleteUser($userID)
    {
        $user = self::getUser($userID);
        $user->delete();
    }
}
