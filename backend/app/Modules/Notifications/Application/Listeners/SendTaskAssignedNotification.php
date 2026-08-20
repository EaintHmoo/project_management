<?php

namespace App\Modules\Notifications\Application\Listeners;

use App\Modules\Notifications\Infrastructure\Notifications\TaskAssignedNotification;
use App\Modules\Tasks\Domain\Events\TaskAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskAssignedNotification implements ShouldQueue
{
    public function handle(TaskAssigned $event): void
    {
        $assignee = $event->task->assignee;

        if ($assignee === null) {
            return;
        }

        $assignee->notify(new TaskAssignedNotification($event->task, $event->assignedById));
    }
}
