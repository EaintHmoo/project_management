<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Meetings\Domain\Models\Meeting;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tasks\Domain\Models\Comment;
use App\Modules\Tasks\Domain\Models\Label;
use App\Modules\Tasks\Domain\Models\Task;
use App\Modules\Teams\Domain\Models\Team;
use App\Modules\Tenancy\Domain\Enums\MembershipStatus;
use App\Modules\Tenancy\Domain\Enums\OrganizationRole;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $alice = User::factory()->create([
                'name' => 'Alice Morgan',
                'email' => 'alice@example.com',
            ]);

            $bob = User::factory()->create([
                'name' => 'Bob Chen',
                'email' => 'bob@example.com',
            ]);

            $sarah = User::factory()->create([
                'name' => 'Sarah Lin',
                'email' => 'sarah@example.com',
            ]);

            $organization = Organization::create([
                'owner_id' => $alice->id,
                'name' => 'Nexus Collaboration',
                'slug' => 'nexus-collaboration',
                'timezone' => 'Asia/Yangon',
            ]);

            $organization->members()->attach($alice->id, ['role' => OrganizationRole::Owner, 'status' => MembershipStatus::Active, 'joined_at' => now()]);
            $organization->members()->attach($bob->id, ['role' => OrganizationRole::Manager, 'status' => MembershipStatus::Active, 'joined_at' => now()]);
            $organization->members()->attach($sarah->id, ['role' => OrganizationRole::Member, 'status' => MembershipStatus::Active, 'joined_at' => now()]);

            $engineering = Team::create([
                'organization_id' => $organization->id,
                'name' => 'Engineering',
                'description' => 'Builds the Laravel API, realtime events, and platform foundations.',
            ]);

            $design = Team::create([
                'organization_id' => $organization->id,
                'name' => 'Product Design',
                'description' => 'Owns workflows, UX quality, and collaboration patterns.',
            ]);

            $platform = Project::create([
                'organization_id' => $organization->id,
                'team_id' => $engineering->id,
                'name' => 'Core Collaboration Platform',
                'key' => 'CORE',
                'description' => 'Multi-tenant SaaS foundation with projects, tasks, comments, notifications, and audit logs.',
                'status' => 'active',
                'visibility' => 'organization',
                'starts_at' => now()->subWeek()->toDateString(),
                'ends_at' => now()->addWeeks(8)->toDateString(),
            ]);

            $meetings = Project::create([
                'organization_id' => $organization->id,
                'team_id' => $design->id,
                'name' => 'Video-ready Scheduling',
                'key' => 'MEET',
                'description' => 'Calendar, recurring meetings, timezone support, and future video room integration.',
                'status' => 'planning',
                'visibility' => 'organization',
                'starts_at' => now()->toDateString(),
                'ends_at' => now()->addWeeks(6)->toDateString(),
            ]);

            $labels = collect([
                ['name' => 'Backend', 'color' => '#2563eb'],
                ['name' => 'Frontend', 'color' => '#16a34a'],
                ['name' => 'Urgent', 'color' => '#dc2626'],
                ['name' => 'Design', 'color' => '#9333ea'],
                ['name' => 'Security', 'color' => '#f59e0b'],
            ])->map(fn (array $label) => Label::create([
                ...$label,
                'organization_id' => $organization->id,
            ]))->keyBy('name');

            $tasks = collect([
                [
                    'project_id' => $platform->id,
                    'assignee_id' => $alice->id,
                    'reporter_id' => $bob->id,
                    'title' => 'Create organization switcher and tenant context',
                    'description' => 'Keep selected organization state aligned across API requests and dashboard navigation.',
                    'status' => 'todo',
                    'priority' => 'high',
                    'due_at' => now()->addDays(3),
                    'position' => 1,
                    'labels' => ['Frontend', 'Backend'],
                ],
                [
                    'project_id' => $platform->id,
                    'assignee_id' => $bob->id,
                    'reporter_id' => $alice->id,
                    'title' => 'Add RBAC policy map for project and task actions',
                    'description' => 'Model owner, admin, manager, member, and guest access for critical workspace actions.',
                    'status' => 'in_progress',
                    'priority' => 'high',
                    'due_at' => now()->addDays(5),
                    'position' => 1,
                    'labels' => ['Backend', 'Security'],
                ],
                [
                    'project_id' => $platform->id,
                    'assignee_id' => $sarah->id,
                    'reporter_id' => $alice->id,
                    'title' => 'Design task detail comments and mention flow',
                    'description' => 'Support threaded replies, mentions, edit/delete controls, and attachments-ready layout.',
                    'status' => 'review',
                    'priority' => 'medium',
                    'due_at' => now()->addWeek(),
                    'position' => 1,
                    'labels' => ['Design', 'Frontend'],
                ],
                [
                    'project_id' => $meetings->id,
                    'assignee_id' => $alice->id,
                    'reporter_id' => $sarah->id,
                    'title' => 'Store meeting times in UTC with user timezone rendering',
                    'description' => 'Phase 1 scheduling should be ready for Phase 2 video rooms without schema rewrites.',
                    'status' => 'done',
                    'priority' => 'medium',
                    'due_at' => now()->subDay(),
                    'position' => 1,
                    'labels' => ['Backend'],
                ],
            ])->map(function (array $task) use ($organization, $labels) {
                $labelNames = $task['labels'];
                unset($task['labels']);

                $task = Task::create([
                    ...$task,
                    'organization_id' => $organization->id,
                ]);

                $task->labels()->attach(collect($labelNames)->map(fn (string $name) => $labels[$name]->id)->all());

                return $task;
            });

            Comment::create([
                'task_id' => $tasks[1]->id,
                'user_id' => $alice->id,
                'body' => '@Bob Please include tenant isolation tests with the first policy pass.',
                'mentions' => [$bob->id],
            ]);

            $standup = Meeting::create([
                'organization_id' => $organization->id,
                'host_id' => $alice->id,
                'title' => 'Engineering Daily Standup',
                'description' => 'Daily status, blockers, and task movement.',
                'starts_at' => now()->setTime(2, 30),
                'ends_at' => now()->setTime(2, 45),
                'timezone' => 'UTC',
                'status' => 'scheduled',
                'recurrence_rule' => 'FREQ=WEEKLY;BYDAY=MO,TU,WE,TH,FR',
                'video_room_provider' => null,
                'video_room_id' => null,
            ]);

            $planning = Meeting::create([
                'organization_id' => $organization->id,
                'host_id' => $bob->id,
                'title' => 'Sprint Planning',
                'description' => 'Biweekly scope and capacity planning.',
                'starts_at' => now()->addDays(4)->setTime(3, 30),
                'ends_at' => now()->addDays(4)->setTime(4, 30),
                'timezone' => 'UTC',
                'status' => 'scheduled',
                'recurrence_rule' => 'FREQ=WEEKLY;INTERVAL=2;BYDAY=MO',
            ]);

            $standup->participants()->attach([$alice->id, $bob->id, $sarah->id]);
            $planning->participants()->attach([$alice->id, $bob->id, $sarah->id]);
        });
    }
}
