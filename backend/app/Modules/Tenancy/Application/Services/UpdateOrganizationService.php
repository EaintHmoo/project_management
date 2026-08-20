<?php

namespace App\Modules\Tenancy\Application\Services;

use App\Modules\Tenancy\Domain\Contracts\OrganizationRepositoryInterface;
use App\Modules\Tenancy\Domain\Models\Organization;

final class UpdateOrganizationService
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizations,
    ) {}

    /**
     * @param  array{name?: string, timezone?: string}  $attributes
     */
    public function execute(Organization $organization, array $attributes): Organization
    {
        return $this->organizations->update($organization, $attributes);
    }
}
