<?php

namespace App\Modules\Auth\Infrastructure\UserAgent;

use App\Modules\Auth\Domain\ValueObjects\ClientDevice;

/**
 * Lightweight best-effort user-agent parser so the security event pipeline
 * doesn't need an external dependency. Not exhaustive; swap for a dedicated
 * package (e.g. jenssegers/agent) if richer detection is ever needed.
 */
class UserAgentParser
{
    public function parse(?string $userAgent): ClientDevice
    {
        if (! $userAgent) {
            return new ClientDevice;
        }

        return new ClientDevice(
            deviceType: $this->detectDeviceType($userAgent),
            browser: $this->detectBrowser($userAgent),
            os: $this->detectOs($userAgent),
        );
    }

    private function detectDeviceType(string $userAgent): string
    {
        if (preg_match('/iPad|Tablet/i', $userAgent)) {
            return 'tablet';
        }

        if (preg_match('/Mobile|iPhone|Android/i', $userAgent)) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function detectBrowser(string $userAgent): ?string
    {
        return match (true) {
            str_contains($userAgent, 'Edg/') => 'Edge',
            str_contains($userAgent, 'OPR/') => 'Opera',
            str_contains($userAgent, 'Chrome/') => 'Chrome',
            str_contains($userAgent, 'CriOS/') => 'Chrome',
            str_contains($userAgent, 'Firefox/') => 'Firefox',
            str_contains($userAgent, 'Safari/') && str_contains($userAgent, 'Version/') => 'Safari',
            default => null,
        };
    }

    private function detectOs(string $userAgent): ?string
    {
        return match (true) {
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Mac OS X') => 'macOS',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad') => 'iOS',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => null,
        };
    }
}
