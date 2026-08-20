<?php

namespace App\Modules\Tenancy\Presentation\Http\Resources;

use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Organization */
class OrganizationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'timezone' => $this->timezone,
            'owner_id' => $this->owner_id,
            'my_role' => $this->whenPivotLoaded('organization_members', fn () => $this->pivot->role),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
