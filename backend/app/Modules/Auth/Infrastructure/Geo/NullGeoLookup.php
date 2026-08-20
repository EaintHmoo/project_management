<?php

namespace App\Modules\Auth\Infrastructure\Geo;

use App\Modules\Auth\Domain\Contracts\GeoLookup;
use App\Modules\Auth\Domain\ValueObjects\GeoLocation;

/**
 * Default no-op geo provider so the security event pipeline works with zero
 * external dependencies. Bind a real MaxMind/IP-API implementation in
 * AuthServiceProvider when one is available.
 */
class NullGeoLookup implements GeoLookup
{
    public function lookup(string $ipAddress): GeoLocation
    {
        return new GeoLocation;
    }
}
