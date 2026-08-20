<?php

namespace App\Modules\Meetings\Application\Services;

use App\Modules\Meetings\Domain\Enums\ParticipantResponse;
use App\Modules\Meetings\Domain\Models\Meeting;

final class RespondToMeetingService
{
    public function execute(Meeting $meeting, int $userId, ParticipantResponse $response): Meeting
    {
        $meeting->participants()->updateExistingPivot($userId, [
            'response_status' => $response->value,
        ]);

        return $meeting->load('participants');
    }
}
