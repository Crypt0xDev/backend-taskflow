<?php

namespace App\Modules\Task\Actions;

use App\Models\User;
use App\Modules\Task\Task;
use Illuminate\Database\Eloquent\Collection;

class ListTrashedTasksAction
{
    public function execute(User $user): Collection
    {
        return Task::onlyTrashed()
            ->with('category')
            ->where('user_id', $user->id)
            ->latest('deleted_at')
            ->get();
    }
}
