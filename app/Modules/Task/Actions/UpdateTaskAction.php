<?php

namespace App\Modules\Task\Actions;

use App\Modules\Task\Task;

class UpdateTaskAction
{
    public function execute(Task $task, array $data): Task
    {
        $tagIds = $data['tag_ids'] ?? null;
        unset($data['tag_ids']);
        $task->update($data);
        if ($tagIds !== null) {
            $task->tags()->sync($tagIds);
        }
        return $task->load(['category', 'tags']);
    }
}
