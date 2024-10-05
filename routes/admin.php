<?php

use App\Http\Controllers\v1\Admin\ClasseController;
use App\Http\Controllers\v1\Admin\LevelController;
use App\Http\Controllers\v1\Admin\PermissionController;
use App\Http\Controllers\v1\Admin\PermissionToRoleController;
use App\Http\Controllers\v1\Admin\RoleController;
use App\Http\Controllers\v1\Admin\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/admin')->group(function () {
    Route::resource('/classes', ClasseController::class);
    Route::post('/classes/students/add', [ClasseController::class, 'addStudentsToClasse']);
    Route::delete('/classes/students/remove', [ClasseController::class, 'removeStudentsFromClasse']);

    Route::get('/teachers', [UserController::class, 'getTeachers']);
    Route::get('/levels', [LevelController::class, 'index']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::resource('/users', UserController::class);
        Route::resource('/permissions', PermissionController::class);
        Route::resource('/roles', RoleController::class);
        //manage permissions to roles
        Route::post('/roles/assign-permission', [PermissionToRoleController::class, 'assignPermissionToRole']);
        Route::post('/roles/remove-permission', [PermissionToRoleController::class, 'removePermissionFromRole']);
    });
});
