<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\v1\Admin\RoleService;
use App\Http\Requests\Admin\RoleRequest;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles =  $this->roleService->getAllRoles();
        return $this->sendResponse($roles, "Roles retrieved successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $role = $this->roleService->createRole($request->validated());
        return $this->sendResponse($role, "Role created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = $this->roleService->getRoleById($id);
        return $this->sendResponse($role, "Role retrieved successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleService->updateRole($request->validated(), $id);
        return $this->sendResponse($role, "Role updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = $this->roleService->deleteRole($id);
        return $this->sendResponse($role, "Role deleted successfully");
    }
}
