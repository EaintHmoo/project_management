<?php

namespace App\Modules\Tenancy\Tests\Feature;

use App\Models\User;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RbacAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function makeOrganizationWithMember(OrganizationRole $role): array
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $organization = Organization::create([
            'owner_id' => $owner->id,
            'name' => 'Nexus',
            'slug' => 'nexus-'.Str::random(8),
            'timezone' => 'UTC',
        ]);

        $organization->members()->attach($owner->id, [
            'role' => OrganizationRole::Owner,
            'status' => MembershipStatus::Active,
            'joined_at' => now(),
        ]);

        $organization->members()->attach($member->id, [
            'role' => $role,
            'status' => MembershipStatus::Active,
            'joined_at' => now(),
        ]);

        return [$owner, $member, $organization];
    }

    public function test_a_member_cannot_update_organization_settings_but_an_admin_can(): void
    {
        [, $member, $organization] = $this->makeOrganizationWithMember(OrganizationRole::Member);

        $this->actingAs($member)
            ->patchJson("/api/organizations/{$organization->id}", ['name' => 'Renamed'])
            ->assertStatus(403);

        [, $admin, $adminOrganization] = $this->makeOrganizationWithMember(OrganizationRole::Admin);

        $this->actingAs($admin)
            ->patchJson("/api/organizations/{$adminOrganization->id}", ['name' => 'Renamed'])
            ->assertOk();
    }

    public function test_a_stranger_cannot_view_an_organization_they_do_not_belong_to(): void
    {
        [, , $organization] = $this->makeOrganizationWithMember(OrganizationRole::Member);
        $stranger = User::factory()->create();

        $this->actingAs($stranger)
            ->getJson("/api/organizations/{$organization->id}")
            ->assertStatus(403);
    }

    public function test_a_guest_cannot_create_a_project_but_a_manager_can(): void
    {
        [, $guest, $organization] = $this->makeOrganizationWithMember(OrganizationRole::Guest);

        $this->actingAs($guest)
            ->postJson("/api/organizations/{$organization->id}/projects", ['name' => 'Blocked', 'key' => 'BLK'])
            ->assertStatus(403);

        [, $manager, $managerOrganization] = $this->makeOrganizationWithMember(OrganizationRole::Manager);

        $this->actingAs($manager)
            ->postJson("/api/organizations/{$managerOrganization->id}/projects", ['name' => 'Allowed', 'key' => 'ALW'])
            ->assertCreated();
    }

    public function test_a_member_cannot_invite_others_but_a_manager_can(): void
    {
        [, $member, $organization] = $this->makeOrganizationWithMember(OrganizationRole::Member);
        $invitee = User::factory()->create();

        $this->actingAs($member)
            ->postJson("/api/organizations/{$organization->id}/invitations", [
                'email' => $invitee->email,
                'role' => OrganizationRole::Member->value,
            ])
            ->assertStatus(403);

        [, $manager, $managerOrganization] = $this->makeOrganizationWithMember(OrganizationRole::Manager);
        $secondInvitee = User::factory()->create();

        $this->actingAs($manager)
            ->postJson("/api/organizations/{$managerOrganization->id}/invitations", [
                'email' => $secondInvitee->email,
                'role' => OrganizationRole::Member->value,
            ])
            ->assertCreated();
    }

    public function test_a_member_can_create_a_task_but_only_the_author_can_edit_their_own_comment(): void
    {
        [, $member, $organization] = $this->makeOrganizationWithMember(OrganizationRole::Member);

        $project = Project::create([
            'organization_id' => $organization->id,
            'name' => 'Core',
            'key' => 'CORE',
            'status' => 'active',
            'visibility' => 'organization',
        ]);

        $taskId = $this->actingAs($member)
            ->postJson("/api/projects/{$project->id}/tasks", ['title' => 'Do the thing'])
            ->assertCreated()
            ->json('data.id');

        $commentId = $this->actingAs($member)
            ->postJson("/api/tasks/{$taskId}/comments", ['body' => 'First'])
            ->assertCreated()
            ->json('data.id');

        $otherMember = User::factory()->create();
        $organization->members()->attach($otherMember->id, [
            'role' => OrganizationRole::Member,
            'status' => MembershipStatus::Active,
            'joined_at' => now(),
        ]);

        $this->actingAs($otherMember)
            ->patchJson("/api/tasks/{$taskId}/comments/{$commentId}", ['body' => 'Hijacked'])
            ->assertStatus(403);

        $this->actingAs($member)
            ->patchJson("/api/tasks/{$taskId}/comments/{$commentId}", ['body' => 'Edited by author'])
            ->assertOk();
    }

    public function test_a_member_cannot_remove_another_member_but_an_admin_can(): void
    {
        [$owner, $member, $organization] = $this->makeOrganizationWithMember(OrganizationRole::Member);

        $targetMembershipId = $organization->memberships()->where('user_id', $member->id)->first()->id;

        $anotherMember = User::factory()->create();
        $organization->members()->attach($anotherMember->id, [
            'role' => OrganizationRole::Member,
            'status' => MembershipStatus::Active,
            'joined_at' => now(),
        ]);

        $this->actingAs($anotherMember)
            ->deleteJson("/api/organizations/{$organization->id}/members/{$targetMembershipId}")
            ->assertStatus(403);

        $this->actingAs($owner)
            ->deleteJson("/api/organizations/{$organization->id}/members/{$targetMembershipId}")
            ->assertOk();
    }
}
