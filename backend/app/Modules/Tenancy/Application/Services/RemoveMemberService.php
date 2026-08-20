<?php

namespace App\Modules\Tenancy\Application\Services;

use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use Illuminate\Validation\ValidationException;

final class RemoveMemberService
{
    public function execute(OrganizationMember $membership): void
    {
        if ($membership->role === OrganizationRole::Owner) {
            throw ValidationException::withMessages([
                'member' => 'The organization owner cannot be removed.',
            ]);
        }

        $membership->delete();
    }
}
