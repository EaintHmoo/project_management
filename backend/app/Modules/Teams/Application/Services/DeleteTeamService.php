<?php

namespace App\Modules\Teams\Application\Services;

use App\Modules\Teams\Domain\Models\Team;

final class DeleteTeamService
{
    public function execute(Team $team): void
    {
        $team->delete();
    }
}
