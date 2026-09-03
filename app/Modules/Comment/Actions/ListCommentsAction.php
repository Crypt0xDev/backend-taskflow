<?php

namespace App\Modules\Comment\Actions;

use App\Modules\Comment\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListCommentsAction
{
    public function execute(): LengthAwarePaginator
    {
        return Comment::query()
            ->with('user')
            ->latest()
            ->latest('id')
            ->paginate(20);
    }
}
