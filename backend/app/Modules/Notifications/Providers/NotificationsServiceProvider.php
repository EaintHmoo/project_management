<?php

namespace App\Modules\Notifications\Providers;

use App\Modules\Notifications\Application\Listeners\SendMemberInvitedNotification;
use App\Modules\Notifications\Application\Listeners\SendTaskAssignedNotification;
use App\Modules\Notifications\Application\Listeners\SendUserMentionedNotification;
use App\Modules\Notifications\Presentation\Console\FlagOverdueTasks;
use App\Modules\Notifications\Presentation\Console\SendMeetingReminders;
use App\Modules\Tasks\Domain\Events\TaskAssigned;
use App\Modules\Tasks\Domain\Events\UserMentionedInComment;
use App\Modules\Tenancy\Domain\Events\MemberInvited;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(TaskAssigned::class, SendTaskAssignedNotification::class);
        Event::listen(UserMentionedInComment::class, SendUserMentionedNotification::class);
        Event::listen(MemberInvited::class, SendMemberInvitedNotification::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                SendMeetingReminders::class,
                FlagOverdueTasks::class,
            ]);
        }
    }
}
