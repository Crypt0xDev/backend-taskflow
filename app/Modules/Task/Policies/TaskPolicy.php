<?php

namespace App\Modules\Task\Policies;

use App\Models\User;
use App\Modules\Task\Task;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        return $this->owns($user, $task);
    }

    public function update(User $user, Task $task): bool
    {
        return $this->owns($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->owns($user, $task);
    }

    public function restore(User $user, Task $task): bool
    {
        return $this->owns($user, $task);
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $this->owns($user, $task);
    }

    private function owns(User $user, Task $task): bool
    {
        return $user->id === $task->user_id || $user->isAdmin();
    }
}
