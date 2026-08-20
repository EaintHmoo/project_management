<?php

namespace App\Modules\Meetings\Tests\Feature;

use App\Models\User;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeetingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_host_can_schedule_a_meeting_and_a_participant_can_respond(): void
    {
        $host = User::factory()->create();
        $participant = User::factory()->create();

        $organization = Organization::create([
            'owner_id' => $host->id,
            'name' => 'Nexus',
            'slug' => 'nexus',
            'timezone' => 'UTC',
        ]);

        foreach ([$host, $participant] as $user) {
            $organization->members()->attach($user->id, [
                'role' => OrganizationRole::Member,
                'status' => MembershipStatus::Active,
                'joined_at' => now(),
            ]);
        }

        $meetingId = $this->actingAs($host)
            ->postJson("/api/organizations/{$organization->id}/meetings", [
                'title' => 'Sprint Planning',
                'starts_at' => '2026-09-01 10:00:00',
                'ends_at' => '2026-09-01 11:00:00',
                'timezone' => 'Asia/Yangon',
                'participant_ids' => [$participant->id],
            ])
            ->assertCreated()
            ->json('data.id');

        $this->assertDatabaseHas('meeting_participants', [
            'meeting_id' => $meetingId,
            'user_id' => $host->id,
            'response_status' => 'accepted',
        ]);

        $this->actingAs($participant)
            ->postJson("/api/organizations/{$organization->id}/meetings/{$meetingId}/respond", [
                'response' => 'accepted',
            ])
            ->assertOk();

        $this->assertDatabaseHas('meeting_participants', [
            'meeting_id' => $meetingId,
            'user_id' => $participant->id,
            'response_status' => 'accepted',
        ]);
    }
}
