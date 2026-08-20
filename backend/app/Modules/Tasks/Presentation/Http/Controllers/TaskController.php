<?php

namespace App\Modules\Tasks\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tasks\Application\Services\AssignTaskService;
use App\Modules\Tasks\Application\Services\CreateTaskService;
use App\Modules\Tasks\Application\Services\DeleteTaskService;
use App\Modules\Tasks\Application\Services\MoveTaskService;
use App\Modules\Tasks\Application\Services\UpdateTaskService;
use App\Modules\Tasks\Domain\Contracts\TaskRepositoryInterface;
use App\Modules\Tasks\Domain\DTOs\CreateTaskData;
use App\Modules\Tasks\Domain\Enums\TaskPriority;
use App\Modules\Tasks\Domain\Enums\TaskStatus;
use App\Modules\Tasks\Domain\Models\Task;
use App\Modules\Tasks\Presentation\Http\Requests\AssignTaskRequest;
use App\Modules\Tasks\Presentation\Http\Requests\CreateTaskRequest;
use App\Modules\Tasks\Presentation\Http\Requests\MoveTaskRequest;
use App\Modules\Tasks\Presentation\Http\Requests\UpdateTaskRequest;
use App\Modules\Tasks\Presentation\Http\Resources\TaskResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
    ) {}

    public function index(Request $request, Project $project): JsonResponse
    {
        $this->authorize('viewAny', [Task::class, $project]);

        $tasks = $this->tasks->forProject($project, $request->only(['status', 'priority', 'assignee_id', 'due_before']));

        $kanban = $tasks->groupBy(fn ($task) => $task->status->value)
            ->map(fn ($group) => TaskResource::collection($group));

        return response()->json([
            'success' => true,
            'data' => [
                'list' => TaskResource::collection($tasks),
                'kanban' => $kanban,
            ],
            'message' => 'Tasks loaded.',
        ]);
    }

    public function store(CreateTaskRequest $request, Project $project, CreateTaskService $service): JsonResponse
    {
        $this->authorize('create', [Task::class, $project]);

        $task = $service->execute(new CreateTaskData(
            organizationId: $project->organization_id,
            projectId: $project->id,
            assigneeId: $request->input('assignee_id'),
            reporterId: $request->user()->id,
            title: $request->string('title')->toString(),
            description: $request->input('description'),
            priority: $request->filled('priority')
                ? TaskPriority::from($request->string('priority')->toString())
                : TaskPriority::Medium,
            dueAt: $request->input('due_at'),
            labelIds: $request->input('label_ids', []),
        ));

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task->load(['assignee', 'reporter'])),
            'message' => 'Task created successfully.',
        ], 201);
    }

    public function show(Project $project, Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task->load(['assignee', 'reporter', 'labels'])->loadCount('comments')),
            'message' => 'Task loaded.',
        ]);
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task, UpdateTaskService $service): JsonResponse
    {
        $this->authorize('update', $task);

        $task = $service->execute($task, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task->load(['assignee', 'reporter'])),
            'message' => 'Task updated successfully.',
        ]);
    }

    public function move(MoveTaskRequest $request, Project $project, Task $task, MoveTaskService $service): JsonResponse
    {
        $this->authorize('update', $task);

        $task = $service->execute(
            $task,
            TaskStatus::from($request->string('status')->toString()),
            $request->integer('position'),
        );

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'message' => 'Task moved successfully.',
        ]);
    }

    public function assign(AssignTaskRequest $request, Project $project, Task $task, AssignTaskService $service): JsonResponse
    {
        $this->authorize('assign', $task);

        $task = $service->execute($task, $request->input('assignee_id'), $request->user()->id);

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task->load('assignee')),
            'message' => 'Task assignee updated successfully.',
        ]);
    }

    public function destroy(Project $project, Task $task, DeleteTaskService $service): JsonResponse
    {
        $this->authorize('delete', $task);

        $service->execute($task);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Task deleted successfully.',
        ]);
    }
}
