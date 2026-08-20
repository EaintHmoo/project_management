<?php

namespace App\Modules\Tenancy\Infrastructure;

use App\Modules\Tenancy\Domain\Contracts\TenantContext;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use RuntimeException;

class RequestTenantContext implements TenantContext
{
    private ?Organization $organization = null;

    private ?OrganizationMember $membership = null;

    public function resolve(Organization $organization, OrganizationMember $membership): void
    {
        $this->organization = $organization;
        $this->membership = $membership;
    }

    public function id(): int
    {
        return $this->organization()->id;
    }

    public function organization(): Organization
    {
        return $this->organization ?? throw new RuntimeException('Tenant context has not been resolved for this request.');
    }

    public function role(): OrganizationRole
    {
        return $this->membership?->role ?? throw new RuntimeException('Tenant context has not been resolved for this request.');
    }
}
