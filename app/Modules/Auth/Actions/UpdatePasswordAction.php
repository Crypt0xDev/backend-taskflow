<?php

namespace App\Modules\Auth\Actions;

use App\Models\User;

class UpdatePasswordAction
{
    public function execute(User $user, array $data): void
    {
        $user->password = $data['password'];
        $user->must_change_password = false;
        $user->save();
    }
}
