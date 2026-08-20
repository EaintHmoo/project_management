<?php

namespace App\Modules\Tasks\Domain\Models;

use App\Models\User;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tasks\Domain\Enums\TaskPriority;
use App\Modules\Tasks\Domain\Enums\TaskStatus;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'organization_id',
        'project_id',
        'assignee_id',
        'reporter_id',
        'title',
        'description',
        'status',
        'priority',
        'due_at',
        'position',
        'overdue_notified_at',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'due_at' => 'datetime',
        'overdue_notified_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
}
