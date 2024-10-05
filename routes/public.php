<?php

use App\Http\Controllers\v1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/public')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']); // Récupérer l'utilisateur connecté
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
