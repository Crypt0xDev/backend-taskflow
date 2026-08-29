<?php

use App\Modules\Access\Permission\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index']);
});
