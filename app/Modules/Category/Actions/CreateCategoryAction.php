<?php

namespace App\Modules\Category\Actions;

use App\Models\User;
use App\Modules\Category\Category;

class CreateCategoryAction
{
    public function execute(User $user, array $data): Category
    {
        $data['user_id'] = $user->id;
        return Category::create($data);
    }
}
