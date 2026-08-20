<?php

namespace App\Modules\Notifications\Application\Listeners;

use App\Modules\Notifications\Infrastructure\Notifications\MemberInvitedNotification;
use App\Modules\Tenancy\Domain\Events\MemberInvited;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMemberInvitedNotification implements ShouldQueue
{
    public function handle(MemberInvited $event): void
    {
        $event->membership->user->notify(new MemberInvitedNotification($event->membership));
    }
}
