<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Webaccess\ProjectSquareLaravel\Models\Platform;
use Webaccess\ProjectSquareLaravel\Repositories\EloquentUserRepository;

class UserManager
{
    public function __construct()
    {
        $this->repository = new EloquentUserRepository();
    }

    public function getAgencyUsersPaginatedList()
    {
        return $this->repository->getAgencyUsersPaginatedList(env('USERS_PER_PAGE', 10));
    }

    public function getAgencyUsers()
    {
        return $this->repository->getAgencyUsers();
    }

    public function getUsersByRole($roleID = null)
    {
        return $this->repository->getUsersByRole($roleID);
    }

    public function getUsersByClient($clientID)
    {
        return $this->repository->getClientUsers($clientID);
    }

    public function getUsersByProject($projectID)
    {
        if (!$projectID) {
            return $this->getUsers();
        }

        return $this->repository->getUsersByProject($projectID);
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

    public function createUser($firstName, $lastName, $email, $password, $mobile=null, $phone=null, $clientID=null, $clientRole=null, $isAdministrator=null)
    {
        if ($user = $this->repository->getUserByEmail($email)) {
            throw new \Exception(trans('projectsquare::users.email_already_existing_error'));
        }

        if ($platform = Platform::first()) {

            if (isset($platform->users_limit) && sizeof($this->getAgencyUsers()) >= $platform->users_limit) {
                throw new \Exception(trans('projectsquare::users.users_limit_reached'));
            }

            $userID = $this->repository->createUser($firstName, $lastName, $email, Hash::make($password), $mobile, $phone, $clientID, $clientRole, $isAdministrator);

            //Insert notification settings
            $keys = [
                'EMAIL_NOTIFICATION_TASK_CREATED',
                'EMAIL_NOTIFICATION_TASK_UPDATED',
                'EMAIL_NOTIFICATION_TASK_DELETED',
                'EMAIL_NOTIFICATION_TICKET_CREATED',
                'EMAIL_NOTIFICATION_TICKET_UPDATED',
                'EMAIL_NOTIFICATION_TICKET_DELETED',
                'EMAIL_NOTIFICATION_MESSAGE_CREATED',
            ];

            foreach ($keys as $key) {
                app()->make('SettingManager')->createOrUpdateUserSetting(
                    $userID,
                    $key,
                    true
                );
            }

            //Send email
            Mail::send('projectsquare::emails.user_account_created', array('email' => $email, 'first_name' => $firstName, 'last_name' => $lastName, 'password' => $password, 'url' => isset($platform->url) ? $platform->url : $platform->url), function ($message) use ($email) {
                $message->to($email)
                    ->from('no-reply@projectsquare.io')
                    ->subject('[projectsquare] Votre compte a été créé avec succès');
            });
        }
    }

    public function updateUser($userID, $firstName, $lastName, $email, $password=null, $mobile=null, $phone=null, $clientID=null, $clientRole=null, $isAdministrator=false)
    {
        $user = $this->repository->getUserByEmail($email);
        if ($user && $user->id != $userID) {
            throw new \Exception(trans('projectsquare::users.email_already_existing_error'));
        }

        $this->repository->updateUser($userID, $firstName, $lastName, $email, ($password) ? Hash::make($password) : null, $mobile, $phone, $clientID, $clientRole, $isAdministrator);
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
                null,
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
                ->from('no-reply@projectsquare.io')
                ->subject('[projectsquare] Votre nouveau mot de passe');
        });
    }
}
