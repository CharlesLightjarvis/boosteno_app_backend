<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\v1\Admin\PermissionService;
use App\Http\Requests\Admin\PermissionRequest;

class PermissionController extends BaseController
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions =  $this->permissionService->getAllPermissions();
        return $this->sendResponse($permissions, "Permissions retrieved successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        $role = $this->permissionService->createPermission($request->validated());
        return $this->sendResponse($role, "Permission created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permission = $this->permissionService->getPermissionById($id);
        return $this->sendResponse($permission, "Permission retrieved successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, $id)
    {
        $permission = $this->permissionService->updatePermission($request->validated(), $id);
        return $this->sendResponse($permission, "Permission updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = $this->permissionService->deletePermission($id);
        return $this->sendResponse($permission, "Permission deleted successfully");
    }
}
