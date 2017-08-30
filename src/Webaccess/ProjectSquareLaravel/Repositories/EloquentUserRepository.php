<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Ramsey\Uuid\Uuid;
use Webaccess\ProjectSquare\Entities\User as UserEntity;
use Webaccess\ProjectSquareLaravel\Models\Message;
use Webaccess\ProjectSquareLaravel\Models\Project;
use Webaccess\ProjectSquareLaravel\Models\User;
use Webaccess\ProjectSquare\Repositories\UserRepository;

class EloquentUserRepository implements UserRepository
{
    public function getUserModel($userID)
    {
        return User::find($userID);
    }

    public function getAgencyUsersPaginatedList($limit, $sortColumn = null, $sortOrder = null)
    {
        return User::whereNull('client_id')->orderBy($sortColumn ? $sortColumn : 'updated_at', $sortOrder ? $sortOrder : 'DESC')->paginate($limit);
    }

    public function getAgencyUsers()
    {
        return User::whereNull('client_id')->get();
    }

    public function getClientUsers($clientID)
    {
        return User::where('client_id', '=', $clientID)->get();
    }

    public function getUsers()
    {
        return User::all();
    }

    public function getUser($userID)
    {
        $user = null;
        if ($userModel = $this->getUserModel($userID)) {
            $user = self::getEntityFromModel($userModel);
        }

        return $user;
    }

    public function getUserByEmail($userEmail)
    {
        $user = null;
        if ($userModel = User::where('email', '=', $userEmail)->first()) {
            $user = self::getEntityFromModel($userModel);
        }

        return $user;
    }

    public function getUsersByProject($projectID)
    {
        return Project::find($projectID)->users;
    }

    public function getUsersByRole($roleID)
    {
        return ($roleID) ? User::where('role_id', '=', $roleID)->get() : $this->getAgencyUsers();
    }

    public function createUser($firstName, $lastName, $email, $password, $mobile, $phone, $clientID, $clientRole, $roleID, $isAdministrator=false)
    {
        $user = new User();
        $userID = Uuid::uuid4()->toString();
        $user->id = $userID;
        $user->save();
        self::updateUser($userID, $firstName, $lastName, $email, $password, $mobile, $phone, $clientID, $clientRole, $roleID, $isAdministrator);

        return $userID;
    }

    public function updateUser($userID, $firstName, $lastName, $email, $password, $mobile, $phone, $clientID, $clientRole, $roleID, $isAdministrator=false)
    {
        if ($user = self::getUserModel($userID)) {
            if ($firstName != null) {
                $user->first_name = $firstName;
            }
            if ($lastName != null) {
                $user->last_name = $lastName;
            }
            if ($email != null) {
                $user->email = $email;
            }
            if ($password) {
                $user->password = $password;
            }
            if ($mobile) {
                $user->mobile = $mobile;
            }
            if ($phone) {
                $user->phone = $phone;
            }
            if ($clientID != null) {
                $user->client_id = $clientID;
            }
            if ($clientRole != null) {
                $user->client_role = $clientRole;
            }
            if ($roleID != null) {
                $user->role_id = $roleID;
            }
            if ($isAdministrator !== false) {
                $user->is_administrator = $isAdministrator;
            }
            $user->save();
        }
    }

    public function deleteUser($userID)
    {
        $user = self::getUserModel($userID);
        $user->projects()->detach();
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

    public static function getEntityFromModel($userModel)
    {
        $user = new UserEntity();
        $user->id = $userModel->id;
        $user->email = $userModel->email;
        $user->firstName = $userModel->first_name;
        $user->lastName = $userModel->last_name;
        $user->password = $userModel->password;
        $user->mobile = $userModel->mobile;
        $user->phone = $userModel->phone;
        $user->clientID = $userModel->client_id;
        $user->clientRole = $userModel->client_role;
        $user->roleID = $userModel->role_id;
        $user->isAdministrator = $userModel->is_administrator;

        return $user;
    }
}
