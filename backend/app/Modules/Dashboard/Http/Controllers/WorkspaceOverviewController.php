<?php

namespace App\Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Meetings\Domain\Models\Meeting;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tasks\Domain\Models\Task;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Http\JsonResponse;

class WorkspaceOverviewController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $organization = Organization::query()
            ->withCount(['members', 'projects'])
            ->first();

        if (! $organization) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Workspace has not been seeded yet.',
            ]);
        }

        $tasksByStatus = Task::query()
            ->where('organization_id', $organization->id)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return response()->json([
            'success' => true,
            'data' => [
                'organization' => $organization,
                'metrics' => [
                    'active_projects' => Project::where('organization_id', $organization->id)->where('status', 'active')->count(),
                    'open_tasks' => Task::where('organization_id', $organization->id)->whereNotIn('status', ['done'])->count(),
                    'completed_tasks' => Task::where('organization_id', $organization->id)->where('status', 'done')->count(),
                    'upcoming_meetings' => Meeting::where('organization_id', $organization->id)->where('starts_at', '>=', now())->count(),
                    'members' => $organization->members_count,
                ],
                'tasks_by_status' => $tasksByStatus,
                'projects' => Project::withCount('tasks')->where('organization_id', $organization->id)->get(),
                'kanban' => Task::with(['assignee:id,name,email', 'labels:id,name,color'])
                    ->where('organization_id', $organization->id)
                    ->orderBy('position')
                    ->get()
                    ->groupBy('status'),
                'meetings' => Meeting::with('participants:id,name,email')
                    ->where('organization_id', $organization->id)
                    ->orderBy('starts_at')
                    ->limit(5)
                    ->get(),
            ],
            'message' => 'Workspace overview loaded.',
        ]);
    }
}
