<?php

namespace App\Modules\Users\Actions;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListUsersAction
{
    public function execute(): LengthAwarePaginator
    {
        return User::with('role')->orderBy('user_name')->paginate(20);
    }
}
