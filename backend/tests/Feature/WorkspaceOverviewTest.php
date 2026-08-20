<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_overview_returns_seeded_product_data(): void
    {
        $this->seed();

        $alice = User::where('email', 'alice@example.com')->firstOrFail();
        $organization = Organization::where('slug', 'nexus-collaboration')->firstOrFail();

        $response = $this->actingAs($alice)
            ->withHeader('X-Organization-Id', (string) $organization->id)
            ->getJson('/api/workspace/overview');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.organization.slug', 'nexus-collaboration')
            ->assertJsonPath('data.metrics.active_projects', 1)
            ->assertJsonCount(2, 'data.projects')
            ->assertJsonStructure([
                'data' => [
                    'metrics' => [
                        'active_projects',
                        'open_tasks',
                        'completed_tasks',
                        'upcoming_meetings',
                        'members',
                    ],
                    'kanban',
                    'meetings',
                ],
            ]);
    }

    public function test_workspace_overview_requires_authentication(): void
    {
        $this->getJson('/api/workspace/overview')->assertUnauthorized();
    }

    public function test_workspace_overview_requires_an_organization_header(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/workspace/overview')
            ->assertStatus(400);
    }

    public function test_workspace_overview_rejects_non_members(): void
    {
        $this->seed();

        $outsider = User::factory()->create();
        $organization = Organization::where('slug', 'nexus-collaboration')->firstOrFail();

        $this->actingAs($outsider)
            ->withHeader('X-Organization-Id', (string) $organization->id)
            ->getJson('/api/workspace/overview')
            ->assertForbidden();
    }
}
