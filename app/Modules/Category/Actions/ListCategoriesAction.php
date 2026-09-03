<?php

namespace App\Modules\Category\Actions;

use App\Models\User;
use App\Modules\Category\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListCategoriesAction
{
    public function execute(User $user, bool $all = false): LengthAwarePaginator
    {
        return Category::withCount('tasks')
            ->when(! ($all && $user->isAdmin()), fn($query) => $query->where('user_id', $user->id))
            ->orderBy('name')
            ->paginate(20);
    }
}
