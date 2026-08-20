<?php

namespace App\Modules\Projects\Domain\DTOs;

use App\Modules\Projects\Domain\Enums\ProjectVisibility;

final class CreateProjectData
{
    public function __construct(
        public readonly int $organizationId,
        public readonly ?int $teamId,
        public readonly string $name,
        public readonly string $key,
        public readonly ?string $description,
        public readonly ProjectVisibility $visibility,
        public readonly ?string $startsAt,
        public readonly ?string $endsAt,
    ) {}
}
