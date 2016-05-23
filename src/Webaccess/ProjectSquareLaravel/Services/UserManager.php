<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

    public function createUser($firstName, $lastName, $email, $password, $clientID, $isAdministrator)
    {
        $this->repository->createUser($firstName, $lastName, $email, $password, $clientID, $isAdministrator);
    }

    public function updateUser($userID, $firstName, $lastName, $email, $password, $clientID, $isAdministrator)
    {
        $this->repository->updateUser($userID, $firstName, $lastName, $email, ($password) ? Hash::make($password) : null, $clientID, $isAdministrator);
    }

    public function deleteUser($userID)
    {
        $this->repository->deleteUser($userID);
    }

    public function generateNewPassword($userID)
    {
        if ($user = $this->getUser($userID)) {
            $password = $this->createRandomString();
            $this->repository->updateUser(
                $user->id,
                null,
                null,
                null,
                Hash::make($password),
                null,
                null
            );
            $this->sendNewPasswordToUser($password, $user->email);
        }
    }

    private function createRandomString($length=8)
    {
        $chars = 'abcdefghkmnpqrstuvwxyz23456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; ++$i) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    private function sendNewPasswordToUser($newPassword, $userEmail)
    {
        Mail::send('projectsquare::emails.password', array('password' => $newPassword), function ($message) use ($userEmail) {

            $message->to($userEmail)
                ->from('no-reply@projectsquare.fr')
                ->subject('[projectsquare] Votre nouveau mot de passe');
        });
    }
}
