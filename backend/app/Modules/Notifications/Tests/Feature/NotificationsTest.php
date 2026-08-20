<?php

namespace App\Modules\Notifications\Tests\Feature;

use App\Models\User;
use App\Modules\Notifications\Infrastructure\Notifications\MemberInvitedNotification;
use App\Modules\Notifications\Infrastructure\Notifications\TaskAssignedNotification;
use App\Modules\Notifications\Infrastructure\Notifications\UserMentionedNotification;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{0: User, 1: User, 2: Organization, 3: Project}
     */
    private function makeProjectWithTwoMembers(): array
    {
        $owner = User::factory()->create();
        $teammate = User::factory()->create();

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

        $organization->members()->attach($teammate->id, [
            'role' => OrganizationRole::Member,
            'status' => MembershipStatus::Active,
            'joined_at' => now(),
        ]);

        $project = Project::create([
            'organization_id' => $organization->id,
            'name' => 'Core Platform',
            'key' => 'CORE',
            'status' => 'active',
            'visibility' => 'organization',
        ]);

        return [$owner, $teammate, $organization, $project];
    }

    public function test_assigning_a_task_to_someone_else_notifies_them(): void
    {
        Notification::fake();

        [$owner, $teammate, , $project] = $this->makeProjectWithTwoMembers();

        $taskId = $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks", ['title' => 'Build API'])
            ->json('data.id');

        $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks/{$taskId}/assign", ['assignee_id' => $teammate->id])
            ->assertOk();

        Notification::assertSentTo($teammate, TaskAssignedNotification::class);
        Notification::assertNotSentTo($owner, TaskAssignedNotification::class);
    }

    public function test_self_assignment_does_not_notify(): void
    {
        Notification::fake();

        [$owner, , , $project] = $this->makeProjectWithTwoMembers();

        $taskId = $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks", ['title' => 'Build API'])
            ->json('data.id');

        $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks/{$taskId}/assign", ['assignee_id' => $owner->id])
            ->assertOk();

        Notification::assertNothingSent();
    }

    public function test_mentioning_a_user_in_a_comment_notifies_them(): void
    {
        Notification::fake();

        [$owner, $teammate, , $project] = $this->makeProjectWithTwoMembers();

        $taskId = $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks", ['title' => 'Build API'])
            ->json('data.id');

        $this->actingAs($owner)
            ->postJson("/api/tasks/{$taskId}/comments", [
                'body' => 'cc @teammate please review',
                'mentions' => [$teammate->id, $owner->id],
            ])
            ->assertCreated();

        Notification::assertSentTo($teammate, UserMentionedNotification::class);
        Notification::assertNotSentTo($owner, UserMentionedNotification::class);
    }

    public function test_inviting_a_member_notifies_them(): void
    {
        Notification::fake();

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

        Notification::assertSentTo($invitee, MemberInvitedNotification::class);
    }

    public function test_notification_center_lists_marks_read_and_deletes(): void
    {
        [$owner, $teammate, , $project] = $this->makeProjectWithTwoMembers();

        $taskId = $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks", ['title' => 'Build API'])
            ->json('data.id');

        $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks/{$taskId}/assign", ['assignee_id' => $teammate->id])
            ->assertOk();

        $notificationId = $this->actingAs($teammate)
            ->getJson('/api/notifications')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->json('data.0.id');

        $this->actingAs($teammate)
            ->getJson('/api/notifications/unread-count')
            ->assertOk()
            ->assertJsonPath('data.count', 1);

        $this->actingAs($teammate)
            ->postJson("/api/notifications/{$notificationId}/read")
            ->assertOk();

        $this->actingAs($teammate)
            ->getJson('/api/notifications/unread-count')
            ->assertOk()
            ->assertJsonPath('data.count', 0);

        $this->actingAs($teammate)
            ->postJson('/api/notifications/read-all')
            ->assertOk();

        $this->actingAs($teammate)
            ->deleteJson("/api/notifications/{$notificationId}")
            ->assertOk();

        $this->assertDatabaseMissing('notifications', ['id' => $notificationId]);
    }

    public function test_a_user_cannot_read_or_delete_another_users_notification(): void
    {
        [$owner, $teammate, , $project] = $this->makeProjectWithTwoMembers();

        $taskId = $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks", ['title' => 'Build API'])
            ->json('data.id');

        $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks/{$taskId}/assign", ['assignee_id' => $teammate->id])
            ->assertOk();

        $notificationId = $this->actingAs($teammate)
            ->getJson('/api/notifications')
            ->json('data.0.id');

        $this->actingAs($owner)
            ->postJson("/api/notifications/{$notificationId}/read")
            ->assertNotFound();

        $this->actingAs($owner)
            ->deleteJson("/api/notifications/{$notificationId}")
            ->assertNotFound();
    }
}
