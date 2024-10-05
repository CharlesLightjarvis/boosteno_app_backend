<?php

namespace App\Http\Controllers\Services\v1\Admin;


use App\Http\Resources\v1\Admin\PermissionResource;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function getAllPermissions()
    {
        $permissions = Permission::all();
        return PermissionResource::collection($permissions);
    }

    public function getPermissionById($id)
    {
        $permission =  Permission::findOrFail($id);
        return new PermissionResource($permission);
    }

    public function createPermission($data)
    {
        $permission = Permission::create($data);

        return new PermissionResource($permission);
    }

    public function updatePermission($data, $id)
    {
        $permission = Permission::findOrFail($id);

        $permission->update($data);

        return new PermissionResource($permission);
    }

    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return new PermissionResource($permission);
    }
}
