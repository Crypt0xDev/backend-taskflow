<?php

namespace App\Modules\Task\Actions;

use App\Models\User;
use App\Modules\Task\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListTrashedTasksAction
{
    public function execute(User $user, bool $all = false): LengthAwarePaginator
    {
        return Task::onlyTrashed()
            ->with(['category', 'tags'])
            ->when(! ($all && $user->isAdmin()), fn($query) => $query->where('user_id', $user->id))
            ->latest('deleted_at')
            ->paginate(20);
    }
}
