<?php

namespace App\Modules\Tenancy\Presentation\Http\Requests;

use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class InviteMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'role' => ['required', new Enum(OrganizationRole::class)],
        ];
    }
}
