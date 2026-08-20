<?php

namespace App\Modules\Auth\Domain\Contracts;

use App\Modules\Auth\Domain\ValueObjects\GeoLocation;

/**
 * IP-derived approximate location, never precise GPS. Swap the bound
 * implementation to plug in MaxMind, ipapi.co, etc. without touching callers.
 */
interface GeoLookup
{
    public function lookup(string $ipAddress): GeoLocation;
}
