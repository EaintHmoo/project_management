<?php

namespace App\Modules\Projects\Application\Services;

use App\Modules\Projects\Domain\Contracts\ProjectRepositoryInterface;
use App\Modules\Projects\Domain\DTOs\CreateProjectData;
use App\Modules\Projects\Domain\Models\Project;

final class CreateProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projects,
    ) {}

    public function execute(CreateProjectData $data): Project
    {
        return $this->projects->create($data);
    }
}
