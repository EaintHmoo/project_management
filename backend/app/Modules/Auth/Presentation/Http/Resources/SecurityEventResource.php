<?php

namespace App\Modules\Auth\Presentation\Http\Resources;

use App\Modules\Auth\Domain\Models\SecurityEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SecurityEvent */
class SecurityEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'ip_address' => $this->ip_address,
            'location' => [
                'country_code' => $this->country_code,
                'region' => $this->region,
                'city' => $this->city,
                'timezone' => $this->timezone,
            ],
            'device' => [
                'type' => $this->device_type,
                'browser' => $this->browser,
                'os' => $this->os,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
