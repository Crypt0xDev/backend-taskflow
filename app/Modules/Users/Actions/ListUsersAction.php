<?php

namespace App\Modules\Users\Actions;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ListUsersAction
{
    public function execute(Request $request): LengthAwarePaginator
    {
        $perPage = min((int) $request->integer('per_page', 20), 100);

        return User::with('role')->orderBy('user_name')->paginate($perPage ?: 20);
    }
}
