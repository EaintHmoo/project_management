<?php

namespace App\Modules\Tenancy\Application\Services;

use App\Models\User;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use Illuminate\Validation\ValidationException;

final class AcceptInvitationService
{
    public function execute(User $user, OrganizationMember $membership): OrganizationMember
    {
        $this->assertOwnedBy($user, $membership);

        $membership->update([
            'status' => MembershipStatus::Active,
            'joined_at' => now(),
        ]);

        return $membership;
    }

    private function assertOwnedBy(User $user, OrganizationMember $membership): void
    {
        if ($membership->user_id !== $user->id || $membership->status !== MembershipStatus::Invited) {
            throw ValidationException::withMessages([
                'invitation' => 'This invitation is no longer valid.',
            ]);
        }
    }
}
