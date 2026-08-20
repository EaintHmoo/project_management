<?php

namespace App\Modules\Tenancy\Domain\DTOs;

use App\Modules\Tenancy\Domain\Enums\OrganizationRole;

final class InviteMemberData
{
    public function __construct(
        public readonly int $organizationId,
        public readonly string $email,
        public readonly OrganizationRole $role,
        public readonly int $invitedById,
    ) {}
}
