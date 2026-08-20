<?php

namespace App\Modules\Meetings\Domain\Contracts;

use App\Modules\Meetings\Domain\DTOs\CreateMeetingData;
use App\Modules\Meetings\Domain\Models\Meeting;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

interface MeetingRepositoryInterface
{
    public function find(int $id): ?Meeting;

    public function create(CreateMeetingData $data): Meeting;

    public function update(Meeting $meeting, array $attributes): Meeting;

    public function delete(Meeting $meeting): void;

    /**
     * @return Collection<int, Meeting>
     */
    public function forOrganization(Organization $organization, ?string $from = null, ?string $to = null): Collection;
}
