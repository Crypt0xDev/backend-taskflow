<?php

namespace App\Modules\Users\Actions;

use App\Models\User;

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
        if (array_key_exists('role', $data)) {
            $user->role = $data['role'];
        }
        $user->save();
        return $user;
    }
}
