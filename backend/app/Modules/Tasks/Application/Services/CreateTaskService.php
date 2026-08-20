<?php

namespace App\Modules\Tasks\Application\Services;

use App\Modules\Tasks\Domain\Contracts\TaskRepositoryInterface;
use App\Modules\Tasks\Domain\DTOs\CreateTaskData;
use App\Modules\Tasks\Domain\Events\TaskAssigned;
use App\Modules\Tasks\Domain\Models\Task;

final class CreateTaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
    ) {}

    public function execute(CreateTaskData $data): Task
    {
        $task = $this->tasks->create($data);

        if ($data->labelIds !== []) {
            $task->labels()->sync($data->labelIds);
        }

        if ($data->assigneeId !== null && $data->assigneeId !== $data->reporterId) {
            TaskAssigned::dispatch($task, $data->reporterId);
        }

        return $task->load('labels');
    }
}
