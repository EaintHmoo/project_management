<?php

namespace App\Modules\Teams\Presentation\Policies;

use App\Models\User;
use App\Modules\Teams\Domain\Models\Team;
use App\Modules\Tenancy\Domain\Enums\Permission;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Domain\Support\OrganizationAccess;

class TeamPolicy
{
    public function viewAny(User $user, Organization $organization): bool
    {
        return OrganizationAccess::isMember($user, $organization);
    }

    public function view(User $user, Team $team): bool
    {
        return OrganizationAccess::isMember($user, $team->organization);
    }

    public function create(User $user, Organization $organization): bool
    {
        return OrganizationAccess::can($user, $organization, Permission::TeamCreate);
    }

    public function update(User $user, Team $team): bool
    {
        return OrganizationAccess::can($user, $team->organization, Permission::TeamUpdate);
    }

    public function delete(User $user, Team $team): bool
    {
        return OrganizationAccess::can($user, $team->organization, Permission::TeamDelete);
    }
}
