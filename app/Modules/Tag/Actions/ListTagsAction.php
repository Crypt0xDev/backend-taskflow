<?php

namespace App\Modules\Tag\Actions;

use App\Models\User;
use App\Modules\Tag\Tag;
use Illuminate\Database\Eloquent\Collection;

class ListTagsAction
{
    public function execute(User $user): Collection
    {
        return Tag::withCount('tasks')
            ->where('user_id', $user->id)
            ->orderBy('name')
            ->get();
    }
}
