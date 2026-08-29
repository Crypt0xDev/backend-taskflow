<?php

namespace App\Modules\Auth\Actions;

use App\Models\User;

class UpdateProfileAction
{
    public function execute(User $user, array $data): User
    {
        if (array_key_exists('user_name', $data)) {
            $user->user_name = $data['user_name'];
        }
        if (array_key_exists('email', $data)) {
            $user->email = $data['email'];
        }
        if (array_key_exists('birth_date', $data)) {
            $user->birth_date = $data['birth_date'];
        }
        if (array_key_exists('avatar', $data)) {
            $user->avatar = $data['avatar'];
        }
        $user->save();
        return $user;
    }
}
