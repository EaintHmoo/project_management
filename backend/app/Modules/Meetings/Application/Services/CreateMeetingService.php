<?php

namespace App\Modules\Meetings\Application\Services;

use App\Modules\Meetings\Domain\Contracts\MeetingRepositoryInterface;
use App\Modules\Meetings\Domain\DTOs\CreateMeetingData;
use App\Modules\Meetings\Domain\Enums\ParticipantResponse;
use App\Modules\Meetings\Domain\Models\Meeting;

final class CreateMeetingService
{
    public function __construct(
        private readonly MeetingRepositoryInterface $meetings,
    ) {}

    public function execute(CreateMeetingData $data): Meeting
    {
        $meeting = $this->meetings->create($data);

        $participantIds = array_unique([...$data->participantIds, $data->hostId]);

        $meeting->participants()->attach($participantIds, [
            'response_status' => ParticipantResponse::Pending->value,
        ]);
        $meeting->participants()->updateExistingPivot($data->hostId, [
            'response_status' => ParticipantResponse::Accepted->value,
        ]);

        return $meeting->load('participants');
    }
}
