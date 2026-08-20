<?php

namespace App\Modules\Meetings\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'starts_at' => ['sometimes', 'date'],
            'ends_at' => ['sometimes', 'date', 'after:starts_at'],
            'timezone' => ['sometimes', 'string', 'timezone'],
            'recurrence_rule' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
