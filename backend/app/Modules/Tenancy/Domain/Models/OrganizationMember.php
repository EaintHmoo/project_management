<?php

namespace App\Modules\Tenancy\Domain\Models;

use App\Models\User;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationMember extends Pivot
{
    public $incrementing = true;

    protected $table = 'organization_members';

    protected $fillable = [
        'organization_id',
        'user_id',
        'role',
        'status',
        'invited_by_id',
        'invited_at',
        'joined_at',
    ];

    protected $casts = [
        'role' => OrganizationRole::class,
        'status' => MembershipStatus::class,
        'invited_at' => 'datetime',
        'joined_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by_id');
    }
}
