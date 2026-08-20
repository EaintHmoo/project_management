<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_overview_returns_seeded_product_data(): void
    {
        $this->seed();

        $response = $this->getJson('/api/workspace/overview');

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
}
