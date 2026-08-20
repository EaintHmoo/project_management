<?php

namespace App\Modules\Teams\Application\Services;

use App\Modules\Teams\Domain\DTOs\CreateTeamData;
use App\Modules\Teams\Domain\Models\Team;

final class CreateTeamService
{
    public function execute(CreateTeamData $data): Team
    {
        return Team::create([
            'organization_id' => $data->organizationId,
            'name' => $data->name,
            'description' => $data->description,
        ]);
    }
}
