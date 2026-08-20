<?php

namespace App\Modules\Tenancy\Tests\Feature;

use App\Models\User;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_an_organization_and_becomes_its_owner(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/organizations', [
            'name' => 'Acme Inc',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Acme Inc')
            ->assertJsonPath('data.slug', 'acme-inc');

        $this->assertDatabaseHas('organization_members', [
            'user_id' => $user->id,
            'role' => OrganizationRole::Owner->value,
            'status' => MembershipStatus::Active->value,
        ]);
    }

    public function test_a_user_can_only_list_organizations_they_are_an_active_member_of(): void
    {
        $member = User::factory()->create();
        $stranger = User::factory()->create();

        $organization = Organization::create([
            'owner_id' => $member->id,
            'name' => 'Nexus',
            'slug' => 'nexus',
            'timezone' => 'UTC',
        ]);

        $organization->members()->attach($member->id, [
            'role' => OrganizationRole::Owner,
            'status' => MembershipStatus::Active,
            'joined_at' => now(),
        ]);

        $this->actingAs($member)->getJson('/api/organizations')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->actingAs($stranger)->getJson('/api/organizations')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_invite_accept_and_decline_flow(): void
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();

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
            ->postJson("/api/organizations/{$organization->id}/invitations", [
                'email' => $invitee->email,
                'role' => OrganizationRole::Member->value,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('organization_members', [
            'user_id' => $invitee->id,
            'status' => MembershipStatus::Invited->value,
        ]);

        $membershipId = $organization->memberships()->where('user_id', $invitee->id)->first()->id;

        $this->actingAs($invitee)
            ->getJson('/api/invitations')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->actingAs($invitee)
            ->postJson("/api/invitations/{$membershipId}/accept")
            ->assertOk();

        $this->assertDatabaseHas('organization_members', [
            'user_id' => $invitee->id,
            'status' => MembershipStatus::Active->value,
        ]);
    }
}
