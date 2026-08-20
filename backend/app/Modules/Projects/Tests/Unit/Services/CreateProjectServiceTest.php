<?php

namespace App\Modules\Projects\Tests\Unit\Services;

use App\Modules\Projects\Application\Services\CreateProjectService;
use App\Modules\Projects\Domain\Contracts\ProjectRepositoryInterface;
use App\Modules\Projects\Domain\DTOs\CreateProjectData;
use App\Modules\Projects\Domain\Enums\ProjectVisibility;
use App\Modules\Projects\Domain\Models\Project;
use Mockery;
use Tests\TestCase;

class CreateProjectServiceTest extends TestCase
{
    public function test_it_delegates_creation_to_the_repository(): void
    {
        $data = new CreateProjectData(
            organizationId: 1,
            teamId: null,
            name: 'Core Platform',
            key: 'CORE',
            description: null,
            visibility: ProjectVisibility::Organization,
            startsAt: null,
            endsAt: null,
        );

        $project = new Project;

        $repository = Mockery::mock(ProjectRepositoryInterface::class);
        $repository->shouldReceive('create')->once()->with($data)->andReturn($project);

        $service = new CreateProjectService($repository);

        $this->assertSame($project, $service->execute($data));
    }
}
