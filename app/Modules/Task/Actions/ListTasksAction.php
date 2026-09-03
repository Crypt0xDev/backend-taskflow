<?php

namespace App\Modules\Task\Actions;

use App\Models\User;
use App\Modules\Task\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListTasksAction
{
    public function execute(User $user, ?string $q = null, bool $all = false): LengthAwarePaginator
    {
        return Task::with(['category', 'tags'])
            ->when(! ($all && $user->isAdmin()), fn($query) => $query->where('user_id', $user->id))
            ->when($q, fn($query) => $query->where('title', 'ilike', '%' . addcslashes($q, '%_\\') . '%'))
            ->latest()
            ->paginate(20);
    }
}
