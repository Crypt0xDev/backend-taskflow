<?php

namespace App\Modules\Access\Role;

use App\Http\Controllers\Controller;
use App\Modules\Access\Role\Actions\SaveRoleAction;
use App\Modules\Access\Role\Requests\StoreRoleRequest;
use App\Modules\Access\Role\Requests\UpdateRoleRequest;
use App\Modules\Access\Role\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Role::class);
        $roles = Role::with('permissions')->withCount('permissions')->orderBy('name')->get();
        return RoleResource::collection($roles);
    }

    public function show(Role $role): RoleResource
    {
        $this->authorize('view', $role);
        return RoleResource::make($role->load('permissions'));
    }

    public function store(StoreRoleRequest $request, SaveRoleAction $action): JsonResponse
    {
        $this->authorize('create', Role::class);
        $role = $action->execute(new Role(), $request->validated());
        return RoleResource::make($role)->response()->setStatusCode(201);
    }

    public function update(UpdateRoleRequest $request, Role $role, SaveRoleAction $action): RoleResource
    {
        $this->authorize('update', $role);
        return RoleResource::make($action->execute($role, $request->validated()));
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('delete', $role);
        $role->delete();
        return response()->json(['message' => 'Rol eliminado.']);
    }
}
