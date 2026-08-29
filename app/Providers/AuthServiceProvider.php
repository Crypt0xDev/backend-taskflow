<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Modules\Category\Category;
use App\Modules\Category\Policies\CategoryPolicy;
use App\Modules\Comment\Comment;
use App\Modules\Comment\Policies\CommentPolicy;
use App\Modules\Tag\Tag;
use App\Modules\Tag\Policies\TagPolicy;
use App\Modules\Task\Task;
use App\Modules\Task\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Task::class => TaskPolicy::class,
        Category::class => CategoryPolicy::class,
        Comment::class => CommentPolicy::class,
        Tag::class => TagPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
