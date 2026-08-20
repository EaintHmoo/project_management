<?php

namespace App\Modules\Meetings\Presentation\Http\Requests;

use App\Modules\Meetings\Domain\Enums\ParticipantResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RespondToMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'response' => ['required', new Enum(ParticipantResponse::class)],
        ];
    }
}
