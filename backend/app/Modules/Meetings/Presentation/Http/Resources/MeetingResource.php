<?php

namespace App\Modules\Meetings\Presentation\Http\Resources;

use App\Modules\Meetings\Domain\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Meeting */
class MeetingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'host_id' => $this->host_id,
            'title' => $this->title,
            'description' => $this->description,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'timezone' => $this->timezone,
            'status' => $this->status,
            'recurrence_rule' => $this->recurrence_rule,
            'video_room_provider' => $this->video_room_provider,
            'video_room_id' => $this->video_room_id,
            'participants' => $this->whenLoaded('participants', fn () => $this->participants->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'response_status' => $user->pivot->response_status,
            ])),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
