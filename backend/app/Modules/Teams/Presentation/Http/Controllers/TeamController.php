<?php

namespace App\Modules\Teams\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Teams\Application\Services\CreateTeamService;
use App\Modules\Teams\Application\Services\DeleteTeamService;
use App\Modules\Teams\Application\Services\UpdateTeamService;
use App\Modules\Teams\Domain\DTOs\CreateTeamData;
use App\Modules\Teams\Domain\Models\Team;
use App\Modules\Teams\Presentation\Http\Requests\CreateTeamRequest;
use App\Modules\Teams\Presentation\Http\Requests\UpdateTeamRequest;
use App\Modules\Teams\Presentation\Http\Resources\TeamResource;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller
{
    public function index(Organization $organization): JsonResponse
    {
        $this->authorize('viewAny', [Team::class, $organization]);

        $teams = $organization->teams()->withCount('projects')->get();

        return response()->json([
            'success' => true,
            'data' => TeamResource::collection($teams),
            'message' => 'Teams loaded.',
        ]);
    }

    public function store(CreateTeamRequest $request, Organization $organization, CreateTeamService $service): JsonResponse
    {
        $this->authorize('create', [Team::class, $organization]);

        $team = $service->execute(new CreateTeamData(
            organizationId: $organization->id,
            name: $request->string('name')->toString(),
            description: $request->input('description'),
        ));

        return response()->json([
            'success' => true,
            'data' => new TeamResource($team),
            'message' => 'Team created successfully.',
        ], 201);
    }

    public function show(Organization $organization, Team $team): JsonResponse
    {
        $this->authorize('view', $team);

        return response()->json([
            'success' => true,
            'data' => new TeamResource($team->loadCount('projects')),
            'message' => 'Team loaded.',
        ]);
    }

    public function update(UpdateTeamRequest $request, Organization $organization, Team $team, UpdateTeamService $service): JsonResponse
    {
        $this->authorize('update', $team);

        $team = $service->execute($team, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new TeamResource($team),
            'message' => 'Team updated successfully.',
        ]);
    }

    public function destroy(Organization $organization, Team $team, DeleteTeamService $service): JsonResponse
    {
        $this->authorize('delete', $team);

        $service->execute($team);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Team deleted successfully.',
        ]);
    }
}
