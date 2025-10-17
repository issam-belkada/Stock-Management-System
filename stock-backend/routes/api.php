<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);

    // ✅ Protect with permissions
    Route::post('users/create', [UserController::class, 'store'])
        ->middleware('permission:create_user');

    Route::delete('users/{id}', [UserController::class, 'destroy'])
        ->middleware('permission:delete_user');
});
