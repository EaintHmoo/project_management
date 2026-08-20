<?php

namespace App\Modules\Tasks\Providers;

use App\Modules\Tasks\Domain\Contracts\TaskRepositoryInterface;
use App\Modules\Tasks\Domain\Models\Comment;
use App\Modules\Tasks\Domain\Models\Label;
use App\Modules\Tasks\Domain\Models\Task;
use App\Modules\Tasks\Infrastructure\Repositories\EloquentTaskRepository;
use App\Modules\Tasks\Presentation\Policies\CommentPolicy;
use App\Modules\Tasks\Presentation\Policies\LabelPolicy;
use App\Modules\Tasks\Presentation\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(Label::class, LabelPolicy::class);
    }
}
