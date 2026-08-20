<?php

namespace App\Modules\Tasks\Application\Services;

use App\Modules\Tasks\Domain\Contracts\TaskRepositoryInterface;
use App\Modules\Tasks\Domain\Models\Task;

final class DeleteTaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
    ) {}

    public function execute(Task $task): void
    {
        $this->tasks->delete($task);
    }
}
