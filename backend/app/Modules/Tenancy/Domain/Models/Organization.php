<?php

namespace App\Modules\Tenancy\Domain\Models;

use App\Models\User;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tasks\Domain\Models\Label;
use App\Modules\Teams\Domain\Models\Team;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = ['owner_id', 'name', 'slug', 'timezone'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_members')
            ->using(OrganizationMember::class)
            ->withPivot(['role', 'status', 'invited_by_id', 'invited_at', 'joined_at'])
            ->withTimestamps();
    }

    public function activeMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('status', MembershipStatus::Active->value);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class);
    }
}
