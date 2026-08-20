<?php

namespace App\Modules\Tasks\Presentation\Http\Resources;

use App\Modules\Tasks\Domain\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Task */
class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_at' => $this->due_at,
            'position' => $this->position,
            'assignee' => $this->whenLoaded('assignee'),
            'reporter' => $this->whenLoaded('reporter'),
            'labels' => LabelResource::collection($this->whenLoaded('labels')),
            'comments_count' => $this->whenCounted('comments'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
