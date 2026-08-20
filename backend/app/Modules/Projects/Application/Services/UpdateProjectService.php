<?php

namespace App\Modules\Projects\Application\Services;

use App\Modules\Projects\Domain\Contracts\ProjectRepositoryInterface;
use App\Modules\Projects\Domain\Models\Project;

final class UpdateProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects,
    ) {}

    public function execute(Project $project, array $attributes): Project
    {
        return $this->projects->update($project, $attributes);
    }
}
