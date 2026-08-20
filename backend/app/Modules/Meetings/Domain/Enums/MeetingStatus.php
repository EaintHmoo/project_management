<?php

namespace App\Modules\Meetings\Domain\Enums;

enum MeetingStatus: string
{
    case Scheduled = 'scheduled';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
}
