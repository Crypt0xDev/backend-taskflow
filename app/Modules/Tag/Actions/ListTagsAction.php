<?php

namespace App\Modules\Tag\Actions;

use App\Models\User;
use App\Modules\Tag\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListTagsAction
{
    public function execute(User $user, bool $all = false): LengthAwarePaginator
    {
        return Tag::withCount('tasks')
            ->when(! ($all && $user->isAdmin()), fn($query) => $query->where('user_id', $user->id))
            ->orderBy('name')
            ->paginate(20);
    }
}
