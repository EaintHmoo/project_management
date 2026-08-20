<?php

namespace App\Modules\Tenancy\Infrastructure\Repositories;

use App\Models\User;
use App\Modules\Tenancy\Domain\Contracts\OrganizationRepositoryInterface;
use App\Modules\Tenancy\Domain\DTOs\CreateOrganizationData;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class EloquentOrganizationRepository implements OrganizationRepositoryInterface
{
    public function find(int $id): ?Organization
    {
        return Organization::find($id);
    }

    public function findBySlug(string $slug): ?Organization
    {
        return Organization::where('slug', $slug)->first();
    }

    public function create(CreateOrganizationData $data): Organization
    {
        return Organization::create([
            'owner_id' => $data->ownerId,
            'name' => $data->name,
            'slug' => $data->slug,
            'timezone' => $data->timezone,
        ]);
    }

    public function update(Organization $organization, array $attributes): Organization
    {
        $organization->update($attributes);

        return $organization->refresh();
    }

    public function forUser(User $user): Collection
    {
        return $user->organizations()->wherePivot('status', 'active')->get();
    }
}
