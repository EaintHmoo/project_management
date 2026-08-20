<?php

namespace App\Modules\Projects\Infrastructure\Repositories;

use App\Modules\Projects\Domain\Contracts\ProjectRepositoryInterface;
use App\Modules\Projects\Domain\DTOs\CreateProjectData;
use App\Modules\Projects\Domain\Enums\ProjectStatus;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function find(int $id): ?Project
    {
        return Project::find($id);
    }

    public function create(CreateProjectData $data): Project
    {
        return Project::create([
            'organization_id' => $data->organizationId,
            'team_id' => $data->teamId,
            'name' => $data->name,
            'key' => strtoupper($data->key),
            'description' => $data->description,
            'status' => ProjectStatus::Planning,
            'visibility' => $data->visibility,
            'starts_at' => $data->startsAt,
            'ends_at' => $data->endsAt,
        ]);
    }

    public function update(Project $project, array $attributes): Project
    {
        $project->update($attributes);

        return $project->refresh();
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }

    public function forOrganization(Organization $organization): Collection
    {
        return Project::withCount('tasks')
            ->where('organization_id', $organization->id)
            ->latest()
            ->get();
    }
}
