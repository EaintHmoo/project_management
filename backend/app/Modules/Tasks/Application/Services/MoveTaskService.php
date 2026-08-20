<?php

namespace App\Modules\Tasks\Application\Services;

use App\Modules\Tasks\Domain\Enums\TaskStatus;
use App\Modules\Tasks\Domain\Models\Task;

final class MoveTaskService
{
    /**
     * Move a task to a new Kanban column/status and reorder it within that column.
     */
    public function execute(Task $task, TaskStatus $status, int $position): Task
    {
        $task->update([
            'status' => $status,
            'position' => $position,
        ]);

        return $task;
    }
}
