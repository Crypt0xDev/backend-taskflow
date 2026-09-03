<?php

namespace App\Modules\Tag\Policies;

use App\Models\User;
use App\Modules\Tag\Tag;

class TagPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, Tag $tag): bool
    {
        return $this->owns($user, $tag);
    }

    public function update(User $user, Tag $tag): bool
    {
        return $this->owns($user, $tag);
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $this->owns($user, $tag);
    }

    public function restore(User $user, Tag $tag): bool
    {
        return $this->owns($user, $tag);
    }

    public function forceDelete(User $user, Tag $tag): bool
    {
        return $this->owns($user, $tag);
    }

    private function owns(User $user, Tag $tag): bool
    {
        return $user->id === $tag->user_id || $user->isAdmin();
    }
}
