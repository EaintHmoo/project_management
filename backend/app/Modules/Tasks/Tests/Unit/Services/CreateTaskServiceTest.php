<?php

namespace App\Modules\Tasks\Tests\Unit\Services;

use App\Modules\Tasks\Application\Services\CreateTaskService;
use App\Modules\Tasks\Domain\Contracts\TaskRepositoryInterface;
use App\Modules\Tasks\Domain\DTOs\CreateTaskData;
use App\Modules\Tasks\Domain\Enums\TaskPriority;
use App\Modules\Tasks\Domain\Models\Task;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mockery;
use Tests\TestCase;

class CreateTaskServiceTest extends TestCase
{
    public function test_it_creates_a_task_without_labels(): void
    {
        $data = new CreateTaskData(
            organizationId: 1,
            projectId: 1,
            assigneeId: null,
            reporterId: 1,
            title: 'Write tests',
            description: null,
            priority: TaskPriority::Medium,
            dueAt: null,
        );

        $task = Mockery::mock(Task::class)->makePartial();
        $task->shouldReceive('load')->with('labels')->andReturnSelf();

        $repository = Mockery::mock(TaskRepositoryInterface::class);
        $repository->shouldReceive('create')->once()->with($data)->andReturn($task);

        $service = new CreateTaskService($repository);

        $this->assertSame($task, $service->execute($data));
    }

    public function test_it_syncs_labels_when_provided(): void
    {
        $data = new CreateTaskData(
            organizationId: 1,
            projectId: 1,
            assigneeId: null,
            reporterId: 1,
            title: 'Write tests',
            description: null,
            priority: TaskPriority::Medium,
            dueAt: null,
            labelIds: [5, 6],
        );

        $task = Mockery::mock(Task::class)->makePartial();
        $labels = Mockery::mock(BelongsToMany::class);
        $labels->shouldReceive('sync')->once()->with([5, 6]);
        $task->shouldReceive('labels')->once()->andReturn($labels);
        $task->shouldReceive('load')->with('labels')->andReturnSelf();

        $repository = Mockery::mock(TaskRepositoryInterface::class);
        $repository->shouldReceive('create')->once()->andReturn($task);

        $service = new CreateTaskService($repository);

        $service->execute($data);
    }
}
