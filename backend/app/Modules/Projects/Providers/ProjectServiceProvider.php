<?php

namespace App\Modules\Projects\Providers;

use App\Modules\Projects\Domain\Contracts\ProjectRepositoryInterface;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Projects\Infrastructure\Repositories\EloquentProjectRepository;
use App\Modules\Projects\Presentation\Policies\ProjectPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ProjectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
    }
}
