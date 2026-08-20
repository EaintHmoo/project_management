<?php

namespace App\Modules\Teams\Domain\DTOs;

final class CreateTeamData
{
    public function __construct(
        public readonly int $organizationId,
        public readonly string $name,
        public readonly ?string $description = null,
    ) {}
}
