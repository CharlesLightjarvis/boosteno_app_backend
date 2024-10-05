<?php

namespace App\Http\Controllers\Services\v1\Admin;

use App\Http\Resources\v1\Admin\RoleResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function getAllRoles()
    {
        $roles = Role::all();
        return RoleResource::collection($roles);
    }

    public function getRoleById($id)
    {
        $role =  Role::findOrFail($id);
        return new RoleResource($role);
    }

    public function createRole($data)
    {
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'web'
            ]);

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            DB::commit();
            return new RoleResource($role);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateRole($data, $id)
    {
        $role = Role::findOrFail($id);
        DB::beginTransaction();
        try {
            $role->update([
                'name' => $data['name'],
                'guard_name' => 'web'
            ]);

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            DB::commit();
            return new RoleResource($role);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        // Vérifier si le rôle est associé à des utilisateurs
        if ($role->users()->exists()) {
            throw new \Exception('Cannot delete this role because it is associated with one or more users.');
        }

        // Si aucun utilisateur n'est associé, on peut supprimer le rôle
        $role->delete();
        return new RoleResource($role);
    }
}
