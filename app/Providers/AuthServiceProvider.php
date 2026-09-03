<?php

namespace App\Providers;

use App\Models\User;
use App\Modules\Access\Role\Policies\RolePolicy;
use App\Modules\Access\Role\Role;
use App\Modules\Category\Category;
use App\Modules\Category\Policies\CategoryPolicy;
use App\Modules\Comment\Comment;
use App\Modules\Comment\Policies\CommentPolicy;
use App\Modules\Tag\Tag;
use App\Modules\Tag\Policies\TagPolicy;
use App\Modules\Task\Task;
use App\Modules\Task\Policies\TaskPolicy;
use App\Modules\Users\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Task::class => TaskPolicy::class,
        Category::class => CategoryPolicy::class,
        Comment::class => CommentPolicy::class,
        Tag::class => TagPolicy::class,
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
