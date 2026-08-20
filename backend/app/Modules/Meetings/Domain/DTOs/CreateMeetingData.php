<?php

namespace App\Modules\Meetings\Domain\DTOs;

final class CreateMeetingData
{
    public function __construct(
        public readonly int $organizationId,
        public readonly int $hostId,
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $startsAtUtc,
        public readonly string $endsAtUtc,
        public readonly string $timezone,
        public readonly ?string $recurrenceRule,
        /** @var list<int> */
        public readonly array $participantIds = [],
    ) {}
}
