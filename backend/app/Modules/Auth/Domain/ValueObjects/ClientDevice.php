<?php

namespace App\Modules\Auth\Domain\ValueObjects;

final class ClientDevice
{
    public function __construct(
        public readonly ?string $deviceType = null,
        public readonly ?string $browser = null,
        public readonly ?string $os = null,
    ) {}
}
