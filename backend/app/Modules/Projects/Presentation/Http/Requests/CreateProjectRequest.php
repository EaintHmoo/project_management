<?php

namespace App\Modules\Projects\Presentation\Http\Requests;

use App\Modules\Projects\Domain\Enums\ProjectVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'team_id' => ['nullable', 'integer', 'exists:teams,id'],
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:12', 'alpha_dash'],
            'description' => ['nullable', 'string'],
            'visibility' => ['nullable', new Enum(ProjectVisibility::class)],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ];
    }
}
