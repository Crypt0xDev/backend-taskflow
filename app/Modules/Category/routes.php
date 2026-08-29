<?php

use App\Modules\Category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categories/trashed', [CategoryController::class, 'trashed']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::post('/categories/{category}/restore', [CategoryController::class, 'restore'])->withTrashed();
    Route::delete('/categories/{category}/force', [CategoryController::class, 'forceDelete'])->withTrashed();
});
