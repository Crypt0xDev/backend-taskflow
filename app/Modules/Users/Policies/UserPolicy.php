<?php

namespace App\Modules\Users\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('users', 'view');
    }

    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->hasPermission('users', 'view');
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('users', 'create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->hasPermission('users', 'update');
    }

    public function resetPassword(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->hasPermission('users', 'update');
    }

    public function delete(User $user, User $model): bool|Response
    {
        if (! $user->isAdmin() && ! $user->hasPermission('users', 'delete')) {
            return false;
        }

        if ($user->id === $model->id) {
            return Response::deny('No puedes eliminar tu propia cuenta.');
        }

        if (User::isLastAdmin($model)) {
            return Response::deny('No puedes eliminar al último administrador.');
        }

        return Response::allow();
    }
}
