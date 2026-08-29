<?php

use App\Modules\Tag\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tags/trashed', [TagController::class, 'trashed']);

    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::get('/tags/{tag}', [TagController::class, 'show']);
    Route::put('/tags/{tag}', [TagController::class, 'update']);
    Route::delete('/tags/{tag}', [TagController::class, 'destroy']);

    Route::post('/tags/{tag}/restore', [TagController::class, 'restore'])->withTrashed();
    Route::delete('/tags/{tag}/force', [TagController::class, 'forceDelete'])->withTrashed();
});
