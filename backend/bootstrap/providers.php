<?php

use App\Modules\Auth\Providers\AuthServiceProvider;
use App\Modules\Meetings\Providers\MeetingServiceProvider;
use App\Modules\Notifications\Providers\NotificationsServiceProvider;
use App\Modules\Projects\Providers\ProjectServiceProvider;
use App\Modules\Tasks\Providers\TaskServiceProvider;
use App\Modules\Teams\Providers\TeamServiceProvider;
use App\Modules\Tenancy\Providers\TenancyServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    TenancyServiceProvider::class,
    TeamServiceProvider::class,
    ProjectServiceProvider::class,
    TaskServiceProvider::class,
    MeetingServiceProvider::class,
    NotificationsServiceProvider::class,
];
