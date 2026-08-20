<?php

namespace App\Modules\Tenancy\Presentation\Policies;

use App\Models\User;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Enums\Permission;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Domain\Support\OrganizationAccess;

class OrganizationPolicy
{
    public function view(User $user, Organization $organization): bool
    {
        return OrganizationAccess::isMember($user, $organization);
    }

    public function update(User $user, Organization $organization): bool
    {
        return OrganizationAccess::can($user, $organization, Permission::OrganizationUpdate);
    }

    public function delete(User $user, Organization $organization): bool
    {
        return OrganizationAccess::roleOf($user, $organization) === OrganizationRole::Owner;
    }

    public function inviteMembers(User $user, Organization $organization): bool
    {
        return OrganizationAccess::can($user, $organization, Permission::MemberInvite);
    }
}
