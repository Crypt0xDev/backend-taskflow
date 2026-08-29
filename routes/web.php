<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => response()->json([
    'name' => config('app.name'),
    'status' => 'ok',
    'message' => 'Backend API is running.',
    'version' => config('app.version'),
    'api' => url('/api/v1'),
]));
