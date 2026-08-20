<?php

namespace App\Modules\Projects\Presentation\Http\Requests;

use App\Modules\Projects\Domain\Enums\ProjectStatus;
use App\Modules\Projects\Domain\Enums\ProjectVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'team_id' => ['sometimes', 'nullable', 'integer', 'exists:teams,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', new Enum(ProjectStatus::class)],
            'visibility' => ['sometimes', new Enum(ProjectVisibility::class)],
            'starts_at' => ['sometimes', 'nullable', 'date'],
            'ends_at' => ['sometimes', 'nullable', 'date', 'after_or_equal:starts_at'],
        ];
    }
}
