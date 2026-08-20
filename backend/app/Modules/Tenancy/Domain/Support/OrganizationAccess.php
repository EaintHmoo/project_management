<?php

namespace App\Modules\Tenancy\Domain\Support;

use App\Models\User;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Enums\Permission;
use App\Modules\Tenancy\Domain\Models\Organization;

/**
 * Single source of truth for "does this user have this permission in this
 * organization". Used directly by Policies so authorization never depends
 * on middleware execution order.
 */
final class OrganizationAccess
{
    public static function roleOf(User $user, Organization $organization): ?OrganizationRole
    {
        $membership = $organization->memberships()
            ->where('user_id', $user->id)
            ->where('status', MembershipStatus::Active->value)
            ->first();

        return $membership?->role;
    }

    public static function isMember(User $user, Organization $organization): bool
    {
        return self::roleOf($user, $organization) !== null;
    }

    public static function can(User $user, Organization $organization, Permission $permission): bool
    {
        $role = self::roleOf($user, $organization);

        return $role !== null && RolePermissions::grants($role, $permission);
    }
}
