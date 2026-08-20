<?php

namespace App\Modules\Tenancy\Domain\Contracts;

use App\Models\User;
use App\Modules\Tenancy\Domain\DTOs\CreateOrganizationData;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

interface OrganizationRepositoryInterface
{
    public function find(int $id): ?Organization;

    public function findBySlug(string $slug): ?Organization;

    public function create(CreateOrganizationData $data): Organization;

    public function update(Organization $organization, array $attributes): Organization;

    /**
     * @return Collection<int, Organization>
     */
    public function forUser(User $user): Collection;
}
