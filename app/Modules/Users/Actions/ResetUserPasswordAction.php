<?php

namespace App\Modules\Users\Actions;

use App\Models\User;

class ResetUserPasswordAction
{
    public function execute(User $user, array $data): void
    {
        $user->password = $data['password'];
        $user->must_change_password = true;
        $user->save();
    }
}
