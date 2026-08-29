<?php

use App\Modules\Comment\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/comments', [CommentController::class, 'index']); // public

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});
