<?php

namespace App\Modules\Comment\Policies;

use App\Models\User;
use App\Modules\Comment\Comment;

class CommentPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->isAdmin();
    }
}
