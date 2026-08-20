<?php

namespace App\Modules\Tenancy\Presentation\Http\Resources;

use App\Modules\Tenancy\Domain\Models\OrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrganizationMember */
class OrganizationMemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'role' => $this->role,
            'status' => $this->status,
            'invited_by_id' => $this->invited_by_id,
            'invited_at' => $this->invited_at,
            'joined_at' => $this->joined_at,
        ];
    }
}
