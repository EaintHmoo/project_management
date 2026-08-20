<?php

namespace App\Modules\Teams\Tests\Feature;

use App\Models\User;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_member_can_create_and_list_teams_for_an_organization(): void
    {
        $owner = User::factory()->create();

        $organization = Organization::create([
            'owner_id' => $owner->id,
            'name' => 'Nexus',
            'slug' => 'nexus',
            'timezone' => 'UTC',
        ]);

        $organization->members()->attach($owner->id, [
            'role' => OrganizationRole::Owner,
            'status' => MembershipStatus::Active,
            'joined_at' => now(),
        ]);

        $this->actingAs($owner)
            ->postJson("/api/organizations/{$organization->id}/teams", ['name' => 'Engineering'])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Engineering');

        $this->actingAs($owner)
            ->getJson("/api/organizations/{$organization->id}/teams")
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
