<?php

use App\Modules\Access\Permission\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'permission:roles,view'])->prefix('admin')->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index']);
});
