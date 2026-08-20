<?php

namespace App\Modules\Tenancy\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tenancy\Application\Services\AcceptInvitationService;
use App\Modules\Tenancy\Application\Services\DeclineInvitationService;
use App\Modules\Tenancy\Application\Services\InviteMemberService;
use App\Modules\Tenancy\Domain\DTOs\InviteMemberData;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use App\Modules\Tenancy\Presentation\Http\Requests\InviteMemberRequest;
use App\Modules\Tenancy\Presentation\Http\Resources\OrganizationMemberResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $invitations = OrganizationMember::with(['organization', 'invitedBy'])
            ->where('user_id', $request->user()->id)
            ->where('status', MembershipStatus::Invited->value)
            ->get();

        return response()->json([
            'success' => true,
            'data' => OrganizationMemberResource::collection($invitations),
            'message' => 'Pending invitations loaded.',
        ]);
    }

    public function store(InviteMemberRequest $request, Organization $organization, InviteMemberService $service): JsonResponse
    {
        $this->authorize('inviteMembers', $organization);

        $membership = $service->execute($organization, new InviteMemberData(
            organizationId: $organization->id,
            email: $request->string('email')->toString(),
            role: OrganizationRole::from($request->string('role')->toString()),
            invitedById: $request->user()->id,
        ));

        return response()->json([
            'success' => true,
            'data' => new OrganizationMemberResource($membership),
            'message' => 'Invitation sent successfully.',
        ], 201);
    }

    public function accept(Request $request, OrganizationMember $membership, AcceptInvitationService $service): JsonResponse
    {
        $this->authorize('accept', $membership);

        $membership = $service->execute($request->user(), $membership);

        return response()->json([
            'success' => true,
            'data' => new OrganizationMemberResource($membership),
            'message' => 'Invitation accepted.',
        ]);
    }

    public function decline(Request $request, OrganizationMember $membership, DeclineInvitationService $service): JsonResponse
    {
        $this->authorize('decline', $membership);

        $service->execute($request->user(), $membership);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Invitation declined.',
        ]);
    }
}
