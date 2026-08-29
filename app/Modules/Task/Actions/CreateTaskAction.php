<?php

namespace App\Modules\Task\Actions;

use App\Models\User;
use App\Modules\Task\Task;

class CreateTaskAction
{
    public function execute(User $user, array $data): Task
    {
        $tagIds = $data['tag_ids'] ?? null;
        unset($data['tag_ids']);
        $data['user_id'] = $user->id;
        $task = Task::create($data);
        if ($tagIds !== null) {
            $task->tags()->sync($tagIds);
        }
        return $task->load(['category', 'tags']);
    }
}
