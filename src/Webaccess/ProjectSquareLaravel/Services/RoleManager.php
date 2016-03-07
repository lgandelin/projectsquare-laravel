<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentRoleRepository;

class RoleManager
{
    public static function getRolesPaginatedList()
    {
        return EloquentRoleRepository::getRolesPaginatedList(env('ROLES_PER_PAGE', 10));
    }

    public static function getRoles()
    {
        return EloquentRoleRepository::getRoles();
    }

    public static function getRole($roleID)
    {
        if (!$role = EloquentRoleRepository::getRole($roleID)) {
            throw new \Exception(trans('projectsquare::roles.role_not_found'));
        }

        return $role;
    }

    public static function createRole($name)
    {
        EloquentRoleRepository::createRole($name);
    }

    public static function updateRole($roleID, $name)
    {
        EloquentRoleRepository::updateRole($roleID, $name);
    }

    public static function deleteRole($roleID)
    {
        EloquentRoleRepository::deleteRole($roleID);
    }
}
