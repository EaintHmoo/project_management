<?php

namespace App\Modules\Tenancy\Presentation\Http\Requests;

use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMemberRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => ['required', new Enum(OrganizationRole::class)],
        ];
    }
}
