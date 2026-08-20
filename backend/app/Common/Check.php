<?php

namespace App\Common;

class Check
{
    public static function hasPermission($permissions, $routeName): bool
    {
        if (auth()->user()->isSuperAdmin() || self::isWhitelistedRoute($routeName)) {
            return true;
        }

        return collect($permissions)->pluck('name')->contains($routeName);
    }

    /**
     * Check if the given route is in the whitelist.
     *
     * @param string $routeName
     * @return bool
     */
    public static function isWhitelistedRoute(string $routeName): bool
    {
        return in_array($routeName, self::whiteListRoutes());
    }

    /**
     * Get the list of whitelisted routes.
     *
     * @return array
     */
    public static function whiteListRoutes(): array
    {
        return [
            'dashboard',
            'profile',
            'users.create',
            'users.edit',
        ];
    }
}
