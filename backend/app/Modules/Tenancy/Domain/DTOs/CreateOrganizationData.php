<?php

namespace App\Modules\Tenancy\Domain\DTOs;

final class CreateOrganizationData
{
    public function __construct(
        public readonly int $ownerId,
        public readonly string $name,
        public readonly ?string $slug = null,
        public readonly string $timezone = 'UTC',
    ) {}
}
