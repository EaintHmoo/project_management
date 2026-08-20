<?php

namespace App\Modules\Teams\Providers;

use App\Modules\Teams\Domain\Models\Team;
use App\Modules\Teams\Presentation\Policies\TeamPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class TeamServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Team::class, TeamPolicy::class);
    }
}
