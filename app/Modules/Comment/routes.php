<?php

use App\Modules\Comment\CommentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'password.fresh'])->group(function () {
    Route::get('/comments', [CommentController::class, 'index']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});
