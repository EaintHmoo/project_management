<?php

namespace App\Modules\Projects\Domain\Models;

use App\Modules\Projects\Domain\Enums\ProjectStatus;
use App\Modules\Projects\Domain\Enums\ProjectVisibility;
use App\Modules\Tasks\Domain\Models\Task;
use App\Modules\Teams\Domain\Models\Team;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'organization_id',
        'team_id',
        'name',
        'key',
        'description',
        'status',
        'visibility',
        'starts_at',
        'ends_at',
        'archived_at',
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
        'visibility' => ProjectVisibility::class,
        'starts_at' => 'date',
        'ends_at' => 'date',
        'archived_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
