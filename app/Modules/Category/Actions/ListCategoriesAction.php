<?php

namespace App\Modules\Category\Actions;

use App\Models\User;
use App\Modules\Category\Category;
use Illuminate\Database\Eloquent\Collection;

class ListCategoriesAction
{
    public function execute(User $user): Collection
    {
        return Category::withCount('tasks')
            ->where('user_id', $user->id)
            ->orderBy('name')
            ->get();
    }
}
