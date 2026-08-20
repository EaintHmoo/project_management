<?php

namespace App\Modules\Meetings\Application\Services;

use App\Modules\Meetings\Domain\Contracts\MeetingRepositoryInterface;
use App\Modules\Meetings\Domain\Models\Meeting;

final class DeleteMeetingService
{
    public function __construct(
        private readonly MeetingRepositoryInterface $meetings,
    ) {}

    public function execute(Meeting $meeting): void
    {
        $this->meetings->delete($meeting);
    }
}
