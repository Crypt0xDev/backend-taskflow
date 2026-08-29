<?php

namespace App\Modules\Access\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PermissionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->get();

        return PermissionResource::collection($permissions);
    }
}
