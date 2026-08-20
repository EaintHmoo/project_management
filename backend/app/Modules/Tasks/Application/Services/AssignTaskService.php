<?php

namespace App\Modules\Tasks\Application\Services;

use App\Modules\Tasks\Domain\Events\TaskAssigned;
use App\Modules\Tasks\Domain\Models\Task;

final class AssignTaskService
{
    public function execute(Task $task, ?int $assigneeId, int $assignedById): Task
    {
        $task->update(['assignee_id' => $assigneeId]);

        if ($assigneeId !== null && $assigneeId !== $assignedById) {
            TaskAssigned::dispatch($task, $assignedById);
        }

        return $task;
    }
}
