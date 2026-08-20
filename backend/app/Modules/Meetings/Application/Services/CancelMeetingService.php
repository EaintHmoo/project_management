<?php

namespace App\Modules\Meetings\Application\Services;

use App\Modules\Meetings\Domain\Enums\MeetingStatus;
use App\Modules\Meetings\Domain\Models\Meeting;

final class CancelMeetingService
{
    public function execute(Meeting $meeting): Meeting
    {
        $meeting->update(['status' => MeetingStatus::Cancelled]);

        return $meeting;
    }
}
