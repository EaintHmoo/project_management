<?php

namespace App\Modules\Tenancy\Application\Services;

use App\Modules\Tenancy\Domain\Contracts\OrganizationRepositoryInterface;
use App\Modules\Tenancy\Domain\DTOs\CreateOrganizationData;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CreateOrganizationService
{
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizations,
    ) {}

    public function execute(CreateOrganizationData $data): Organization
    {
        return DB::transaction(function () use ($data) {
            $organization = $this->organizations->create(
                new CreateOrganizationData(
                    ownerId: $data->ownerId,
                    name: $data->name,
                    slug: $data->slug ?: $this->uniqueSlug($data->name),
                    timezone: $data->timezone,
                ),
            );

            $organization->members()->attach($data->ownerId, [
                'role' => OrganizationRole::Owner,
                'status' => MembershipStatus::Active,
                'joined_at' => now(),
            ]);

            return $organization;
        });
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 1;

        while ($this->organizations->findBySlug($slug) !== null) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
