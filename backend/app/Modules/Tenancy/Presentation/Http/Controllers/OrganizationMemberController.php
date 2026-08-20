<?php

namespace App\Modules\Tenancy\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tenancy\Application\Services\RemoveMemberService;
use App\Modules\Tenancy\Application\Services\UpdateMemberRoleService;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use App\Modules\Tenancy\Presentation\Http\Requests\UpdateMemberRoleRequest;
use App\Modules\Tenancy\Presentation\Http\Resources\OrganizationMemberResource;
use Illuminate\Http\JsonResponse;

class OrganizationMemberController extends Controller
{
    public function index(Organization $organization): JsonResponse
    {
        $this->authorize('view', $organization);

        $members = $organization->memberships()
            ->with('user')
            ->where('status', MembershipStatus::Active->value)
            ->get();

        return response()->json([
            'success' => true,
            'data' => OrganizationMemberResource::collection($members),
            'message' => 'Members loaded.',
        ]);
    }

    public function update(UpdateMemberRoleRequest $request, Organization $organization, OrganizationMember $membership, UpdateMemberRoleService $service): JsonResponse
    {
        $this->authorize('update', $membership);

        $membership = $service->execute($membership, OrganizationRole::from($request->string('role')->toString()));

        return response()->json([
            'success' => true,
            'data' => new OrganizationMemberResource($membership),
            'message' => 'Member role updated successfully.',
        ]);
    }

    public function destroy(Organization $organization, OrganizationMember $membership, RemoveMemberService $service): JsonResponse
    {
        $this->authorize('delete', $membership);

        $service->execute($membership);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Member removed successfully.',
        ]);
    }
}
