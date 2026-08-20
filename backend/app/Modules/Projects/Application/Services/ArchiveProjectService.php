<?php

namespace App\Modules\Projects\Application\Services;

use App\Modules\Projects\Domain\Enums\ProjectStatus;
use App\Modules\Projects\Domain\Models\Project;

final class ArchiveProjectService
{
    public function execute(Project $project): Project
    {
        $project->update([
            'status' => ProjectStatus::Archived,
            'archived_at' => now(),
        ]);

        return $project;
    }
}
