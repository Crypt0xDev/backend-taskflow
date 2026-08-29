<?php

namespace App\Modules\Auth\Actions;

use App\Models\User;

class RegisterUserAction
{
    public function execute(array $data): array
    {
        $user = User::create([
            'user_name' => $data['email'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        $user->refresh();
        $token = $user->createToken('api')->plainTextToken;
        return ['user' => $user, 'token' => $token];
    }
}
