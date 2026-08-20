<?php

namespace App\Modules\Tenancy\Application\Services;

use App\Models\User;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Exceptions\NotAnOrganizationMemberException;
use App\Modules\Tenancy\Domain\Models\Organization;

final class SwitchOrganizationService
{
    public function execute(User $user, Organization $organization): Organization
    {
        $isActiveMember = $organization->memberships()
            ->where('user_id', $user->id)
            ->where('status', MembershipStatus::Active->value)
            ->exists();

        if (! $isActiveMember) {
            throw new NotAnOrganizationMemberException;
        }

        return $organization;
    }
}
