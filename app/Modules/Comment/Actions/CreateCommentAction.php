<?php

namespace App\Modules\Comment\Actions;

use App\Models\User;
use App\Modules\Comment\Comment;

class CreateCommentAction
{
    public function execute(User $user, array $data): Comment
    {
        $data['user_id'] = $user->id;
        return Comment::create($data)->load('user');
    }
}
