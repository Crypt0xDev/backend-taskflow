<?php

namespace App\Modules\Users\Actions;

use App\Models\User;

class CreateUserAction
{
    public function execute(array $data): User
    {
        $user = User::create([
            'email' => $data['email'],
            'user_name' => $data['user_name'] ?? $data['email'],
            'password' => $data['password'],
            'must_change_password' => true,
        ]);
        $user->role = $data['role'];
        $user->save();
        return $user;
    }
}
