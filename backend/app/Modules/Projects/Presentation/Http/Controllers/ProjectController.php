<?php

namespace App\Modules\Projects\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Projects\Application\Services\ArchiveProjectService;
use App\Modules\Projects\Application\Services\CreateProjectService;
use App\Modules\Projects\Application\Services\DeleteProjectService;
use App\Modules\Projects\Application\Services\UpdateProjectService;
use App\Modules\Projects\Domain\Contracts\ProjectRepositoryInterface;
use App\Modules\Projects\Domain\DTOs\CreateProjectData;
use App\Modules\Projects\Domain\Enums\ProjectVisibility;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Projects\Presentation\Http\Requests\CreateProjectRequest;
use App\Modules\Projects\Presentation\Http\Requests\UpdateProjectRequest;
use App\Modules\Projects\Presentation\Http\Resources\ProjectResource;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects,
    ) {}

    public function index(Organization $organization): JsonResponse
    {
        $this->authorize('viewAny', [Project::class, $organization]);

        return response()->json([
            'success' => true,
            'data' => ProjectResource::collection($this->projects->forOrganization($organization)),
            'message' => 'Projects loaded.',
        ]);
    }

    public function store(CreateProjectRequest $request, Organization $organization, CreateProjectService $service): JsonResponse
    {
        $this->authorize('create', [Project::class, $organization]);

        $project = $service->execute(new CreateProjectData(
            organizationId: $organization->id,
            teamId: $request->input('team_id'),
            name: $request->string('name')->toString(),
            key: $request->string('key')->toString(),
            description: $request->input('description'),
            visibility: $request->filled('visibility')
                ? ProjectVisibility::from($request->string('visibility')->toString())
                : ProjectVisibility::Organization,
            startsAt: $request->input('starts_at'),
            endsAt: $request->input('ends_at'),
        ));

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
            'message' => 'Project created successfully.',
        ], 201);
    }

    public function show(Organization $organization, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project->loadCount('tasks')),
            'message' => 'Project loaded.',
        ]);
    }

    public function update(UpdateProjectRequest $request, Organization $organization, Project $project, UpdateProjectService $service): JsonResponse
    {
        $this->authorize('update', $project);

        $project = $service->execute($project, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
            'message' => 'Project updated successfully.',
        ]);
    }

    public function archive(Organization $organization, Project $project, ArchiveProjectService $service): JsonResponse
    {
        $this->authorize('update', $project);

        $project = $service->execute($project);

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
            'message' => 'Project archived successfully.',
        ]);
    }

    public function destroy(Organization $organization, Project $project, DeleteProjectService $service): JsonResponse
    {
        $this->authorize('delete', $project);

        $service->execute($project);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Project deleted successfully.',
        ]);
    }
}
