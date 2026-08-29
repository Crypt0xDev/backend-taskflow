<?php

use App\Modules\Task\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks/trashed', [TaskController::class, 'trashed']);

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    Route::post('/tasks/{task}/restore', [TaskController::class, 'restore'])->withTrashed();
    Route::delete('/tasks/{task}/force', [TaskController::class, 'forceDelete'])->withTrashed();
});
