<?php

namespace App\Modules\Comment\Actions;

use App\Modules\Comment\Comment;
use Illuminate\Database\Eloquent\Collection;

class ListCommentsAction
{
    public function execute(): Collection
    {
        return Comment::with('user')
            ->latest()
            ->latest('id')
            ->get();
    }
}
