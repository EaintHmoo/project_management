<?php

namespace App\Modules\Tenancy\Application\Services;

use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use Illuminate\Validation\ValidationException;

final class UpdateMemberRoleService
{
    public function execute(OrganizationMember $membership, OrganizationRole $role): OrganizationMember
    {
        if ($membership->role === OrganizationRole::Owner) {
            throw ValidationException::withMessages([
                'role' => 'The organization owner\'s role cannot be changed.',
            ]);
        }

        $membership->update(['role' => $role]);

        return $membership;
    }
}
