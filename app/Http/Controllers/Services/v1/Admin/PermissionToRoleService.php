<?php

namespace App\Http\Controllers\Services\v1\Admin;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionToRoleService
{
    public function assignPermissionToRole($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);

        if ($role->hasPermissionTo($permission)) {
            throw new \Exception('The role already has this permission.');
        }

        $role->givePermissionTo($permission);

        return $role;
    }

    public function removePermissionFromRole($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);

        if (!$role->hasPermissionTo($permission)) {
            throw new \Exception('The role does not have this permission.');
        }

        $role->revokePermissionTo($permission);

        return $role;
    }
}
