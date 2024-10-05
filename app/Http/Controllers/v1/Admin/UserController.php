<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\v1\Admin\UserService;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users =  $this->userService->getAllUsers();
        return $this->sendResponse($users, "Users retrieved successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return $this->sendResponse($user, "User created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        return $this->sendResponse($user, "User retrieved successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->userService->updateUser($request->validated(), $id);
        return $this->sendResponse($user, "User updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = $this->userService->deleteUser($id);
        return $this->sendResponse($user, "User deleted successfully");
    }

    public function getTeachers()
    {
        $teachers = User::role('teacher')->get();
        return $this->sendResponse($teachers, "Teachers retrieved successfully");
    }
}
