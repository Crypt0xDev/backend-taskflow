<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => response()->json([
    'name' => config('app.name'),
    'status' => 'ok',
    'message' => 'Bienvenido a TaskFlow API',
    'version' => config('app.version'),
    'api' => url('/api/v1'),
]));
