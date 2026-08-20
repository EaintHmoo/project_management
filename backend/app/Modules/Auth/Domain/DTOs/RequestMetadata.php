<?php

namespace App\Modules\Auth\Domain\DTOs;

final class RequestMetadata
{
    public function __construct(
        public readonly string $ipAddress,
        public readonly ?string $userAgent,
    ) {}
}
