<?php

use App\Modules\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'password.fresh'])->prefix('admin')->group(function () {
    Route::middleware('permission:users,view')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
    });
    Route::middleware('permission:users,create')->post('/users', [UserController::class, 'store']);
    Route::middleware('permission:users,update')->group(function () {
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::put('/users/{user}/password', [UserController::class, 'resetPassword']);
    });
    Route::middleware('permission:users,delete')->delete('/users/{user}', [UserController::class, 'destroy']);
});
