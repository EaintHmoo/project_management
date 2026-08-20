<?php

namespace App\Modules\Tasks\Domain\Contracts;

use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tasks\Domain\DTOs\CreateTaskData;
use App\Modules\Tasks\Domain\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function find(int $id): ?Task;

    public function create(CreateTaskData $data): Task;

    public function update(Task $task, array $attributes): Task;

    public function delete(Task $task): void;

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Task>
     */
    public function forProject(Project $project, array $filters = []): Collection;
}
