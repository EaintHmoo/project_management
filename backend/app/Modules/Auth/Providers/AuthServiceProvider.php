<?php

namespace App\Modules\Auth\Providers;

use App\Modules\Auth\Domain\Contracts\GeoLookup;
use App\Modules\Auth\Infrastructure\Geo\NullGeoLookup;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GeoLookup::class, NullGeoLookup::class);
    }
}
