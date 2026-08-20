<?php

namespace App\Modules\Tasks\Application\Services;

use App\Modules\Tasks\Domain\Contracts\TaskRepositoryInterface;
use App\Modules\Tasks\Domain\Models\Task;

final class UpdateTaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
    ) {}

    public function execute(Task $task, array $attributes): Task
    {
        $labelIds = $attributes['label_ids'] ?? null;
        unset($attributes['label_ids']);

        $task = $this->tasks->update($task, $attributes);

        if ($labelIds !== null) {
            $task->labels()->sync($labelIds);
        }

        return $task->load('labels');
    }
}
