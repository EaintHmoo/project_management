<?php

namespace App\Modules\Tenancy\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tenancy\Application\Services\CreateOrganizationService;
use App\Modules\Tenancy\Application\Services\SwitchOrganizationService;
use App\Modules\Tenancy\Application\Services\UpdateOrganizationService;
use App\Modules\Tenancy\Domain\Contracts\OrganizationRepositoryInterface;
use App\Modules\Tenancy\Domain\DTOs\CreateOrganizationData;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Presentation\Http\Requests\CreateOrganizationRequest;
use App\Modules\Tenancy\Presentation\Http\Requests\UpdateOrganizationRequest;
use App\Modules\Tenancy\Presentation\Http\Resources\OrganizationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizations,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $organizations = $this->organizations->forUser($request->user());

        return response()->json([
            'success' => true,
            'data' => OrganizationResource::collection($organizations),
            'message' => 'Organizations loaded.',
        ]);
    }

    public function store(CreateOrganizationRequest $request, CreateOrganizationService $service): JsonResponse
    {
        $organization = $service->execute(new CreateOrganizationData(
            ownerId: $request->user()->id,
            name: $request->string('name')->toString(),
            slug: $request->input('slug'),
            timezone: $request->input('timezone', 'UTC'),
        ));

        return response()->json([
            'success' => true,
            'data' => new OrganizationResource($organization),
            'message' => 'Organization created successfully.',
        ], 201);
    }

    public function show(Organization $organization): JsonResponse
    {
        $this->authorize('view', $organization);

        return response()->json([
            'success' => true,
            'data' => new OrganizationResource($organization),
            'message' => 'Organization loaded.',
        ]);
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization, UpdateOrganizationService $service): JsonResponse
    {
        $this->authorize('update', $organization);

        $organization = $service->execute($organization, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new OrganizationResource($organization),
            'message' => 'Organization updated successfully.',
        ]);
    }

    public function switchTo(Request $request, Organization $organization, SwitchOrganizationService $service): JsonResponse
    {
        $this->authorize('view', $organization);

        $organization = $service->execute($request->user(), $organization);

        return response()->json([
            'success' => true,
            'data' => new OrganizationResource($organization),
            'message' => 'Switched active organization.',
        ]);
    }
}
