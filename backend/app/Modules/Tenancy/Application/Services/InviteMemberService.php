<?php

namespace App\Modules\Tenancy\Application\Services;

use App\Models\User;
use App\Modules\Tenancy\Domain\DTOs\InviteMemberData;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use Illuminate\Validation\ValidationException;

final class InviteMemberService
{
    public function execute(Organization $organization, InviteMemberData $data): OrganizationMember
    {
        $invitee = User::where('email', $data->email)->first();

        if (! $invitee) {
            throw ValidationException::withMessages([
                'email' => 'No user is registered with this email address.',
            ]);
        }

        if ($organization->memberships()->where('user_id', $invitee->id)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'This user is already invited or a member of the organization.',
            ]);
        }

        return $organization->memberships()->create([
            'user_id' => $invitee->id,
            'role' => $data->role,
            'status' => MembershipStatus::Invited,
            'invited_by_id' => $data->invitedById,
            'invited_at' => now(),
        ]);
    }
}
