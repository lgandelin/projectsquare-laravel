<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquareLaravel\Models\Role;
use Webaccess\ProjectSquare\Repositories\RoleRepository;

class EloquentRoleRepository implements RoleRepository
{
    public static function getRolesPaginatedList($limit)
    {
        return Role::paginate($limit);
    }

    public static function getRoles()
    {
        return Role::all();
    }

    public static function getRole($roleID)
    {
        return Role::find($roleID);
    }

    public static function createRole($name)
    {
        $role = new Role();
        $role->save();
        self::updateRole($role->id, $name);
    }

    public static function updateRole($roleID, $name)
    {
        $role = self::getRole($roleID);
        $role->name = $name;
        $role->save();
    }

    public static function deleteRole($roleID)
    {
        $role = self::getRole($roleID);
        $role->delete();
    }
}
