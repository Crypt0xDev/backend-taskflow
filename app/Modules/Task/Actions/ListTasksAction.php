<?php

namespace App\Modules\Task\Actions;

use App\Models\User;
use App\Modules\Task\Task;
use Illuminate\Database\Eloquent\Collection;

class ListTasksAction
{
    public function execute(User $user, ?string $q = null): Collection
    {
        return Task::with(['category', 'tags'])
            ->where('user_id', $user->id)
            ->when($q, fn($query) => $query->where('title', 'ilike', "%{$q}%"))
            ->latest()
            ->get();
    }
}
