<?php

namespace App\Modules\Access\Role\Policies;

use App\Models\User;
use App\Modules\Access\Role\Role;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    private const SYSTEM_ROLES = ['admin', 'user'];

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Role $role): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Role $role): bool
    {
        return true;
    }

    public function delete(User $user, Role $role): bool|Response
    {
        if (in_array($role->name, self::SYSTEM_ROLES, true)) {
            return Response::deny('No se puede eliminar un rol del sistema.');
        }

        if ($role->users()->exists()) {
            return Response::deny('No se puede eliminar un rol asignado a usuarios.');
        }

        return Response::allow();
    }
}
