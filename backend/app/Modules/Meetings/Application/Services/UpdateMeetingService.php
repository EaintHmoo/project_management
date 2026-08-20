<?php

namespace App\Modules\Meetings\Application\Services;

use App\Modules\Meetings\Domain\Contracts\MeetingRepositoryInterface;
use App\Modules\Meetings\Domain\Models\Meeting;

final class UpdateMeetingService
{
    public function __construct(
        private readonly MeetingRepositoryInterface $meetings,
    ) {}

    public function execute(Meeting $meeting, array $attributes): Meeting
    {
        return $this->meetings->update($meeting, $attributes);
    }
}
