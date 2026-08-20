<?php

namespace App\Modules\Meetings\Infrastructure\Repositories;

use App\Modules\Meetings\Domain\Contracts\MeetingRepositoryInterface;
use App\Modules\Meetings\Domain\DTOs\CreateMeetingData;
use App\Modules\Meetings\Domain\Models\Meeting;
use App\Modules\Tenancy\Domain\Models\Organization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EloquentMeetingRepository implements MeetingRepositoryInterface
{
    public function find(int $id): ?Meeting
    {
        return Meeting::find($id);
    }

    public function create(CreateMeetingData $data): Meeting
    {
        return Meeting::create([
            'organization_id' => $data->organizationId,
            'host_id' => $data->hostId,
            'title' => $data->title,
            'description' => $data->description,
            'starts_at' => Carbon::parse($data->startsAtUtc, $data->timezone)->utc(),
            'ends_at' => Carbon::parse($data->endsAtUtc, $data->timezone)->utc(),
            'timezone' => $data->timezone,
            'recurrence_rule' => $data->recurrenceRule,
        ]);
    }

    public function update(Meeting $meeting, array $attributes): Meeting
    {
        $meeting->update($attributes);

        return $meeting->refresh();
    }

    public function delete(Meeting $meeting): void
    {
        $meeting->delete();
    }

    public function forOrganization(Organization $organization, ?string $from = null, ?string $to = null): Collection
    {
        return Meeting::query()
            ->with('participants:id,name,email')
            ->where('organization_id', $organization->id)
            ->when($from, fn (Builder $q, $from) => $q->where('starts_at', '>=', $from))
            ->when($to, fn (Builder $q, $to) => $q->where('starts_at', '<=', $to))
            ->orderBy('starts_at')
            ->get();
    }
}
