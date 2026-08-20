<?php

namespace App\Modules\Auth\Infrastructure\Security;

use App\Modules\Auth\Domain\Contracts\GeoLookup;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Domain\Enums\SecurityEventType;
use App\Modules\Auth\Domain\Models\SecurityEvent;
use App\Modules\Auth\Infrastructure\UserAgent\UserAgentParser;

class SecurityEventRecorder
{
    public function __construct(
        private readonly GeoLookup $geoLookup,
        private readonly UserAgentParser $userAgentParser,
    ) {}

    /**
     * @param  array<string, mixed>  $metadata
     */
    public function record(SecurityEventType $type, RequestMetadata $request, ?int $userId = null, ?int $organizationId = null, array $metadata = []): SecurityEvent
    {
        $geo = $this->geoLookup->lookup($request->ipAddress);
        $device = $this->userAgentParser->parse($request->userAgent);

        return SecurityEvent::create([
            'user_id' => $userId,
            'organization_id' => $organizationId,
            'type' => $type,
            'ip_address' => $request->ipAddress,
            'country_code' => $geo->countryCode,
            'region' => $geo->region,
            'city' => $geo->city,
            'timezone' => $geo->timezone,
            'user_agent' => $request->userAgent,
            'device_type' => $device->deviceType,
            'browser' => $device->browser,
            'os' => $device->os,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }
}
