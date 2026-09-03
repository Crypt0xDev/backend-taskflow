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
        return $user->isAdmin() || $user->hasPermission('roles', 'view');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->isAdmin() || $user->hasPermission('roles', 'view');
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('roles', 'create');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->isAdmin() || $user->hasPermission('roles', 'update');
    }

    public function delete(User $user, Role $role): bool|Response
    {
        if (!$user->isAdmin() && !$user->hasPermission('roles', 'delete')) {
            return Response::deny('No autorizado.');
        }

        if (in_array($role->name, self::SYSTEM_ROLES, true)) {
            return Response::deny('No se puede eliminar un rol del sistema.');
        }

        if ($role->users()->exists()) {
            return Response::deny('No se puede eliminar un rol asignado a usuarios.');
        }

        return Response::allow();
    }
}
