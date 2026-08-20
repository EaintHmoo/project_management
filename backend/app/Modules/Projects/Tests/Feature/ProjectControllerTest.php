<?php

namespace App\Modules\Projects\Tests\Feature;

use App\Models\User;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_member_can_create_list_and_archive_a_project(): void
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

        $projectId = $this->actingAs($owner)
            ->postJson("/api/organizations/{$organization->id}/projects", [
                'name' => 'Core Platform',
                'key' => 'CORE',
            ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'planning')
            ->json('data.id');

        $this->actingAs($owner)
            ->getJson("/api/organizations/{$organization->id}/projects")
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->actingAs($owner)
            ->postJson("/api/organizations/{$organization->id}/projects/{$projectId}/archive")
            ->assertOk()
            ->assertJsonPath('data.status', 'archived');
    }
}
