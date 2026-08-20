<?php

namespace App\Modules\Tenancy\Presentation\Policies;

use App\Models\User;
use App\Modules\Tenancy\Domain\Enums\Permission;
use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use App\Modules\Tenancy\Domain\Support\OrganizationAccess;

class OrganizationMemberPolicy
{
    public function update(User $user, OrganizationMember $membership): bool
    {
        return OrganizationAccess::can($user, $membership->organization, Permission::MemberRoleUpdate);
    }

    public function delete(User $user, OrganizationMember $membership): bool
    {
        return OrganizationAccess::can($user, $membership->organization, Permission::MemberRemove);
    }

    public function accept(User $user, OrganizationMember $membership): bool
    {
        return $membership->user_id === $user->id;
    }

    public function decline(User $user, OrganizationMember $membership): bool
    {
        return $membership->user_id === $user->id;
    }
}
