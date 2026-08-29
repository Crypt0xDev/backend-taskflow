<?php

use App\Http\Middleware\LogApiRequests;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(LogApiRequests::class)->group(function () {
    require app_path('Modules/Auth/routes.php');
    require app_path('Modules/Task/routes.php');
    require app_path('Modules/Category/routes.php');
    require app_path('Modules/Comment/routes.php');
    require app_path('Modules/Users/routes.php');
    require app_path('Modules/Access/routes.php');
    require app_path('Modules/Tag/routes.php');
});
