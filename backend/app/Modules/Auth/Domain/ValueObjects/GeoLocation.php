<?php

namespace App\Modules\Auth\Domain\ValueObjects;

final class GeoLocation
{
    public function __construct(
        public readonly ?string $countryCode = null,
        public readonly ?string $region = null,
        public readonly ?string $city = null,
        public readonly ?string $timezone = null,
    ) {}
}
