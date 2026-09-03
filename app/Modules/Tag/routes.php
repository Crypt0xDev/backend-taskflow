<?php

use App\Modules\Tag\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'password.fresh'])->group(function () {
    Route::middleware('permission:tags,view')->group(function () {
        Route::get('/tags/trashed', [TagController::class, 'trashed']);
        Route::get('/tags', [TagController::class, 'index']);
        Route::get('/tags/{tag}', [TagController::class, 'show']);
    });
    Route::middleware('permission:tags,create')->post('/tags', [TagController::class, 'store']);
    Route::middleware('permission:tags,update')->put('/tags/{tag}', [TagController::class, 'update']);
    Route::middleware('permission:tags,delete')->group(function () {
        Route::delete('/tags/{tag}', [TagController::class, 'destroy']);
        Route::post('/tags/{tag}/restore', [TagController::class, 'restore'])->withTrashed();
        Route::delete('/tags/{tag}/force', [TagController::class, 'forceDelete'])->withTrashed();
    });
});
