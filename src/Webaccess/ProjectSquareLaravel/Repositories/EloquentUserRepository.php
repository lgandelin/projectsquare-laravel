<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquare\Entities\User as UserEntity;
use Webaccess\ProjectSquareLaravel\Models\Message;
use Webaccess\ProjectSquareLaravel\Models\Project;
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

    public function getUserModel($userID)
    {
        return User::find($userID);
    }

    public function getUser($userID)
    {
        $user = null;
        if ($userModel = $this->getUserModel($userID)) {
            $user = new UserEntity();
            $user->id = $userModel->id;
            $user->email = $userModel->email;
            $user->firstName = $userModel->first_name;
            $user->lastName = $userModel->last_name;
            $user->password = $userModel->password;
            $user->clientID = $userModel->client_id;
            $user->isAdministrator = $userModel->is_administrator;
        }

        return $user;
    }

    public function getUsersByProject($projectID)
    {
        return Project::find($projectID)->users;
    }

    public function createUser($firstName, $lastName, $email, $password, $clientID, $isAdministrator=false)
    {
        $user = new User();
        $user->save();
        self::updateUser($user->id, $firstName, $lastName, $email, $password, $clientID, $isAdministrator);
    }

    public function updateUser($userID, $firstName, $lastName, $email, $password, $clientID, $isAdministrator=false)
    {
        $user = self::getUserModel($userID);
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        if ($password) {
            $user->password = $password;
        }
        $user->client_id = $clientID;
        $user->is_administrator = $isAdministrator;
        $user->save();
    }

    public function deleteUser($userID)
    {
        $user = self::getUserModel($userID);
        $user->delete();
    }

    public function getUnreadMessages($userID)
    {
        $user = User::with('unread_messages')->find($userID);

        return $user->unread_messages()->where('read', '=', false)->get();
    }

    public function setReadFlagMessage($userID, $messageID, $read)
    {
        $user = User::find($userID);
        $message = Message::find($messageID);

        if (!$user->unread_messages->contains($messageID)) {
            $user->unread_messages()->attach($message, ['read' => $read]);
        } else {
            $user->unread_messages()->sync([$messageID => ['read' => $read]]);
        }

        $user->save();
    }
}
