<?php

namespace App\Modules\Users\Actions;

use App\Models\User;
use App\Modules\Access\Role\Role;
use Illuminate\Validation\ValidationException;

class UpdateUserAction
{
    public function execute(User $user, array $data): User
    {
        if (array_key_exists('user_name', $data)) {
            $user->user_name = $data['user_name'];
        }
        if (array_key_exists('email', $data)) {
            $user->email = $data['email'];
        }
        if (array_key_exists('role_id', $data) && (int) $data['role_id'] !== $user->role_id) {
            $nextRoleIsAdmin = Role::where('id', $data['role_id'])->value('name') === 'admin';
            if (! $nextRoleIsAdmin && User::isLastAdmin($user)) {
                throw ValidationException::withMessages([
                    'role_id' => 'No puedes quitar el rol de administrador al último administrador.',
                ]);
            }
            $user->role_id = $data['role_id'];
        }
        $user->save();
        return $user;
    }
}
