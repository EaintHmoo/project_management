<?php

namespace App\Modules\Meetings\Domain\Models;

use App\Models\User;
use App\Modules\Meetings\Domain\Enums\MeetingStatus;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meeting extends Model
{
    protected $fillable = [
        'organization_id',
        'host_id',
        'title',
        'description',
        'starts_at',
        'ends_at',
        'timezone',
        'status',
        'recurrence_rule',
        'video_room_provider',
        'video_room_id',
        'reminder_sent_at',
    ];

    protected $casts = [
        'status' => MeetingStatus::class,
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    /**
     * Meetings are stored in UTC. Video room fields stay null in Phase 1 and are
     * populated by the Video module in Phase 2 without touching this schema.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meeting_participants')
            ->withPivot('response_status')
            ->withTimestamps();
    }
}
