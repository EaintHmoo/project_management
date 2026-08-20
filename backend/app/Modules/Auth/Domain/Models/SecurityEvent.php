<?php

namespace App\Modules\Auth\Domain\Models;

use App\Models\User;
use App\Modules\Auth\Domain\Enums\SecurityEventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityEvent extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'organization_id',
        'type',
        'ip_address',
        'country_code',
        'region',
        'city',
        'timezone',
        'user_agent',
        'device_type',
        'browser',
        'os',
        'metadata',
    ];

    protected $casts = [
        'type' => SecurityEventType::class,
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
