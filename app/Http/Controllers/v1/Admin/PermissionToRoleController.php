<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\v1\Admin\PermissionToRoleService;
use App\Http\Requests\Admin\PermissionToRoleRequest;
use App\Http\Resources\v1\Admin\RoleResource;
use Illuminate\Http\Request;

class PermissionToRoleController extends BaseController
{
    protected $permissionToRoleService;

    public function __construct(PermissionToRoleService $permissionToRoleService)
    {
        $this->permissionToRoleService = $permissionToRoleService;
    }

    public function assignPermissionToRole(PermissionToRoleRequest $request)
    {
        try {
            $role = $this->permissionToRoleService->assignPermissionToRole(
                $request->role_id,
                $request->permission_id
            );

            return $this->sendResponse(new RoleResource($role), 'Permission assigned to role successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 400);
        }
    }

    public function removePermissionFromRole(PermissionToRoleRequest $request)
    {
        try {
            $role = $this->permissionToRoleService->removePermissionFromRole(
                $request->role_id,
                $request->permission_id
            );

            return $this->sendResponse(new RoleResource($role), 'Permission removed from role successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 400);
        }
    }
}
