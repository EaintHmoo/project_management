<?php

namespace App\Modules\Projects\Presentation\Http\Resources;

use App\Modules\Projects\Domain\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'team_id' => $this->team_id,
            'name' => $this->name,
            'key' => $this->key,
            'description' => $this->description,
            'status' => $this->status,
            'visibility' => $this->visibility,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'archived_at' => $this->archived_at,
            'tasks_count' => $this->whenCounted('tasks'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
