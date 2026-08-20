<?php

namespace App\Modules\Teams\Application\Services;

use App\Modules\Teams\Domain\Models\Team;

final class UpdateTeamService
{
    /**
     * @param  array{name?: string, description?: string}  $attributes
     */
    public function execute(Team $team, array $attributes): Team
    {
        $team->update($attributes);

        return $team;
    }
}
