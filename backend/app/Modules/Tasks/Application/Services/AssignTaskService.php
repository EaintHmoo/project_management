<?php

namespace App\Modules\Tasks\Application\Services;

use App\Modules\Tasks\Domain\Models\Task;

final class AssignTaskService
{
    public function execute(Task $task, ?int $assigneeId): Task
    {
        $task->update(['assignee_id' => $assigneeId]);

        return $task;
    }
}
