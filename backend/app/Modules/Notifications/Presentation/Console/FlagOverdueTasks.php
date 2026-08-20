<?php

namespace App\Modules\Notifications\Presentation\Console;

use App\Modules\Notifications\Infrastructure\Notifications\TaskOverdueNotification;
use App\Modules\Tasks\Domain\Enums\TaskStatus;
use App\Modules\Tasks\Domain\Models\Task;
use Illuminate\Console\Command;

class FlagOverdueTasks extends Command
{
    protected $signature = 'notifications:flag-overdue-tasks';

    protected $description = 'Notify assignees (or reporters) of tasks that are past their due date.';

    public function handle(): int
    {
        $tasks = Task::query()
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->where('status', '!=', TaskStatus::Done)
            ->whereNull('overdue_notified_at')
            ->with(['assignee', 'reporter'])
            ->get();

        foreach ($tasks as $task) {
            $recipient = $task->assignee ?? $task->reporter;

            if ($recipient !== null) {
                $recipient->notify(new TaskOverdueNotification($task));
            }

            $task->update(['overdue_notified_at' => now()]);
        }

        $this->info("Flagged {$tasks->count()} overdue task(s).");

        return self::SUCCESS;
    }
}
