<?php

use App\Modules\Access\Role\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'password.fresh'])->prefix('admin')->group(function () {
    Route::middleware('permission:roles,view')->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{role}', [RoleController::class, 'show']);
    });
    Route::middleware('permission:roles,create')->post('/roles', [RoleController::class, 'store']);
    Route::middleware('permission:roles,update')->put('/roles/{role}', [RoleController::class, 'update']);
    Route::middleware('permission:roles,delete')->delete('/roles/{role}', [RoleController::class, 'destroy']);
});
