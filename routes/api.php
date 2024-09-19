<?php

use App\Http\Controllers\v1\Admin\RoleController;
use App\Http\Controllers\v1\Admin\UserController;
use App\Http\Controllers\v1\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('v1/public')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']); // Récupérer l'utilisateur connecté
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('v1/admin')->group(function () {
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
});

// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
