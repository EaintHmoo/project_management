<?php

namespace App\Modules\Notifications\Application\Listeners;

use App\Models\User;
use App\Modules\Notifications\Infrastructure\Notifications\UserMentionedNotification;
use App\Modules\Tasks\Domain\Events\UserMentionedInComment;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserMentionedNotification implements ShouldQueue
{
    public function handle(UserMentionedInComment $event): void
    {
        $mentionedUsers = User::whereIn('id', $event->mentionedUserIds)->get();

        foreach ($mentionedUsers as $user) {
            $user->notify(new UserMentionedNotification($event->comment));
        }
    }
}
