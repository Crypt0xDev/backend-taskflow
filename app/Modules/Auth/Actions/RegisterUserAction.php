<?php

namespace App\Modules\Auth\Actions;

use App\Models\User;
use App\Modules\Access\Role\Role;

class RegisterUserAction
{
    public function execute(array $data): array
    {
        $user = User::create([
            'user_name' => $data['email'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => Role::where('name', 'user')->value('id'),
        ]);
        $user->refresh();
        $token = $user->createToken('api')->plainTextToken;
        return ['user' => $user, 'token' => $token];
    }
}
