<?php

namespace App\Modules\Meetings\Providers;

use App\Modules\Meetings\Domain\Contracts\MeetingRepositoryInterface;
use App\Modules\Meetings\Domain\Models\Meeting;
use App\Modules\Meetings\Infrastructure\Repositories\EloquentMeetingRepository;
use App\Modules\Meetings\Presentation\Policies\MeetingPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class MeetingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MeetingRepositoryInterface::class, EloquentMeetingRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(Meeting::class, MeetingPolicy::class);
    }
}
