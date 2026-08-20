<?php

namespace App\Modules\Tasks\Infrastructure\Repositories;

use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tasks\Domain\Contracts\TaskRepositoryInterface;
use App\Modules\Tasks\Domain\DTOs\CreateTaskData;
use App\Modules\Tasks\Domain\Enums\TaskStatus;
use App\Modules\Tasks\Domain\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function find(int $id): ?Task
    {
        return Task::find($id);
    }

    public function create(CreateTaskData $data): Task
    {
        return Task::create([
            'organization_id' => $data->organizationId,
            'project_id' => $data->projectId,
            'assignee_id' => $data->assigneeId,
            'reporter_id' => $data->reporterId,
            'title' => $data->title,
            'description' => $data->description,
            'status' => TaskStatus::Todo,
            'priority' => $data->priority,
            'due_at' => $data->dueAt,
        ]);
    }

    public function update(Task $task, array $attributes): Task
    {
        $task->update($attributes);

        return $task->refresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    /**
     * Supported filters: status, priority, assignee_id, due_before (ISO date/datetime).
     */
    public function forProject(Project $project, array $filters = []): Collection
    {
        return Task::query()
            ->with(['assignee:id,name,email', 'labels:id,name,color'])
            ->where('project_id', $project->id)
            ->when($filters['status'] ?? null, fn (Builder $q, $status) => $q->where('status', $status))
            ->when($filters['priority'] ?? null, fn (Builder $q, $priority) => $q->where('priority', $priority))
            ->when($filters['assignee_id'] ?? null, fn (Builder $q, $assigneeId) => $q->where('assignee_id', $assigneeId))
            ->when($filters['due_before'] ?? null, fn (Builder $q, $dueBefore) => $q->where('due_at', '<', $dueBefore))
            ->orderBy('position')
            ->get();
    }
}
