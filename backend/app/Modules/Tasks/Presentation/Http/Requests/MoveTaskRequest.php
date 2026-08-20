<?php

namespace App\Modules\Tasks\Presentation\Http\Requests;

use App\Modules\Tasks\Domain\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class MoveTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(TaskStatus::class)],
            'position' => ['required', 'integer', 'min:0'],
        ];
    }
}
