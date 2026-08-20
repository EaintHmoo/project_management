<?php

namespace App\Modules\Meetings\Domain\Enums;

enum ParticipantResponse: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Declined = 'declined';
}
