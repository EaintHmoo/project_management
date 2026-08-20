<?php

namespace App\Modules\Tasks\Tests\Feature;

use App\Models\User;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    private function makeProject(): array
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

        $project = Project::create([
            'organization_id' => $organization->id,
            'name' => 'Core Platform',
            'key' => 'CORE',
            'status' => 'active',
            'visibility' => 'organization',
        ]);

        return [$owner, $organization, $project];
    }

    public function test_a_member_can_create_move_and_filter_tasks_on_the_kanban_board(): void
    {
        [$owner, , $project] = $this->makeProject();

        $taskId = $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks", [
                'title' => 'Build API',
                'priority' => 'high',
            ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'todo')
            ->json('data.id');

        $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks/{$taskId}/move", [
                'status' => 'in_progress',
                'position' => 1,
            ])
            ->assertOk()
            ->assertJsonPath('data.status', 'in_progress');

        $this->actingAs($owner)
            ->getJson("/api/projects/{$project->id}/tasks?priority=high")
            ->assertOk()
            ->assertJsonCount(1, 'data.list');
    }

    public function test_comments_support_mentions_and_threaded_replies(): void
    {
        [$owner, , $project] = $this->makeProject();

        $taskId = $this->actingAs($owner)
            ->postJson("/api/projects/{$project->id}/tasks", ['title' => 'Build API'])
            ->json('data.id');

        $commentId = $this->actingAs($owner)
            ->postJson("/api/tasks/{$taskId}/comments", [
                'body' => '@owner please review',
                'mentions' => [$owner->id],
            ])
            ->assertCreated()
            ->json('data.id');

        $this->actingAs($owner)
            ->postJson("/api/tasks/{$taskId}/comments", [
                'body' => 'Sounds good',
                'parent_id' => $commentId,
            ])
            ->assertCreated();

        $this->actingAs($owner)
            ->getJson("/api/tasks/{$taskId}/comments")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonCount(1, 'data.0.replies');
    }
}
