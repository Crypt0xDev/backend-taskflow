<?php

namespace App\Modules\Category\Policies;

use App\Models\User;
use App\Modules\Category\Category;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, Category $category): bool
    {
        return $this->owns($user, $category);
    }

    public function update(User $user, Category $category): bool
    {
        return $this->owns($user, $category);
    }

    public function delete(User $user, Category $category): bool
    {
        return $this->owns($user, $category);
    }

    public function restore(User $user, Category $category): bool
    {
        return $this->owns($user, $category);
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $this->owns($user, $category);
    }

    private function owns(User $user, Category $category): bool
    {
        return $user->id === $category->user_id || $user->isAdmin();
    }
}
