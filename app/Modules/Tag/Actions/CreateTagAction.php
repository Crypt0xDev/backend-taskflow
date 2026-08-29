<?php

namespace App\Modules\Tag\Actions;

use App\Models\User;
use App\Modules\Tag\Tag;

class CreateTagAction
{
    public function execute(User $user, array $data): Tag
    {
        $data['user_id'] = $user->id;

        return Tag::create($data);
    }
}
