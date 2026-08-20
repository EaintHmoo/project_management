<?php

namespace App\Modules\Tenancy\Domain\Support;

use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Enums\Permission;

/**
 * Static role -> permission grants for the organization RBAC model.
 * Owner always has every permission; other roles are explicit allow-lists.
 */
class RolePermissions
{
    /** @var array<string, list<Permission>> */
    private static array $map = [
        'admin' => [
            Permission::OrganizationUpdate,
            Permission::MemberInvite,
            Permission::MemberRemove,
            Permission::MemberRoleUpdate,
            Permission::TeamCreate,
            Permission::TeamUpdate,
            Permission::TeamDelete,
            Permission::ProjectCreate,
            Permission::ProjectUpdate,
            Permission::ProjectDelete,
            Permission::TaskCreate,
            Permission::TaskUpdate,
            Permission::TaskAssign,
            Permission::TaskDelete,
            Permission::MeetingCreate,
            Permission::MeetingUpdate,
            Permission::MeetingCancel,
            Permission::MeetingDelete,
            Permission::LabelManage,
        ],
        'manager' => [
            Permission::MemberInvite,
            Permission::TeamCreate,
            Permission::TeamUpdate,
            Permission::ProjectCreate,
            Permission::ProjectUpdate,
            Permission::ProjectDelete,
            Permission::TaskCreate,
            Permission::TaskUpdate,
            Permission::TaskAssign,
            Permission::TaskDelete,
            Permission::MeetingCreate,
            Permission::MeetingUpdate,
            Permission::MeetingCancel,
            Permission::MeetingDelete,
            Permission::LabelManage,
        ],
        'member' => [
            Permission::TaskCreate,
            Permission::TaskUpdate,
            Permission::TaskAssign,
            Permission::MeetingCreate,
        ],
        'guest' => [],
    ];

    public static function grants(OrganizationRole $role, Permission $permission): bool
    {
        if ($role === OrganizationRole::Owner) {
            return true;
        }

        return in_array($permission, self::$map[$role->value] ?? [], true);
    }
}
