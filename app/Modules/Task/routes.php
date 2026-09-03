<?php

use App\Modules\Task\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'password.fresh'])->group(function () {
    Route::middleware('permission:tasks,view')->group(function () {
        Route::get('/tasks/trashed', [TaskController::class, 'trashed']);
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::get('/tasks/{task}', [TaskController::class, 'show']);
    });
    Route::middleware('permission:tasks,create')->post('/tasks', [TaskController::class, 'store']);
    Route::middleware('permission:tasks,update')->put('/tasks/{task}', [TaskController::class, 'update']);
    Route::middleware('permission:tasks,delete')->group(function () {
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
        Route::post('/tasks/{task}/restore', [TaskController::class, 'restore'])->withTrashed();
        Route::delete('/tasks/{task}/force', [TaskController::class, 'forceDelete'])->withTrashed();
    });
});
