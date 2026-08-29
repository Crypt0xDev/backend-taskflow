<?php

namespace App\Modules\Access\Role;

use App\Http\Controllers\Controller;
use App\Modules\Access\Role\Actions\SaveRoleAction;
use App\Modules\Access\Role\Requests\StoreRoleRequest;
use App\Modules\Access\Role\Requests\UpdateRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        return RoleResource::collection($roles);
    }

    public function show(Role $role): RoleResource
    {
        return RoleResource::make($role->load('permissions'));
    }

    public function store(StoreRoleRequest $request, SaveRoleAction $action): JsonResponse
    {
        $role = $action->execute(new Role(), $request->validated());
        return RoleResource::make($role)->response()->setStatusCode(201);
    }

    public function update(UpdateRoleRequest $request, Role $role, SaveRoleAction $action): RoleResource
    {
        return RoleResource::make($action->execute($role, $request->validated()));
    }

    public function destroy(Role $role): JsonResponse
    {
        if (in_array($role->name, ['admin', 'user'], true)) {
            return response()->json(['message' => 'No se puede eliminar un rol del sistema.'], 422);
        }
        $role->delete();
        return response()->json(['message' => 'Rol eliminado.']);
    }
}
