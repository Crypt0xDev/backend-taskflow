<?php

use App\Modules\Category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'password.fresh'])->group(function () {
    Route::middleware('permission:categories,view')->group(function () {
        Route::get('/categories/trashed', [CategoryController::class, 'trashed']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{category}', [CategoryController::class, 'show']);
    });
    Route::middleware('permission:categories,create')->post('/categories', [CategoryController::class, 'store']);
    Route::middleware('permission:categories,update')->put('/categories/{category}', [CategoryController::class, 'update']);
    Route::middleware('permission:categories,delete')->group(function () {
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
        Route::post('/categories/{category}/restore', [CategoryController::class, 'restore'])->withTrashed();
        Route::delete('/categories/{category}/force', [CategoryController::class, 'forceDelete'])->withTrashed();
    });
});
