<?php

namespace App\Modules\Projects\Domain\Contracts;

use App\Modules\Projects\Domain\DTOs\CreateProjectData;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    public function find(int $id): ?Project;

    public function create(CreateProjectData $data): Project;

    public function update(Project $project, array $attributes): Project;

    public function delete(Project $project): void;

    /**
     * @return Collection<int, Project>
     */
    public function forOrganization(Organization $organization): Collection;
}
