<?php

namespace App\Modules\Tasks\Presentation\Policies;

use App\Models\User;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tasks\Domain\Models\Task;
use App\Modules\Tenancy\Domain\Enums\Permission;
use App\Modules\Tenancy\Domain\Support\OrganizationAccess;

class TaskPolicy
{
    public function viewAny(User $user, Project $project): bool
    {
        return OrganizationAccess::isMember($user, $project->organization);
    }

    public function view(User $user, Task $task): bool
    {
        return OrganizationAccess::isMember($user, $task->organization);
    }

    public function create(User $user, Project $project): bool
    {
        return OrganizationAccess::can($user, $project->organization, Permission::TaskCreate);
    }

    public function update(User $user, Task $task): bool
    {
        return OrganizationAccess::can($user, $task->organization, Permission::TaskUpdate);
    }

    public function assign(User $user, Task $task): bool
    {
        return OrganizationAccess::can($user, $task->organization, Permission::TaskAssign);
    }

    public function delete(User $user, Task $task): bool
    {
        return OrganizationAccess::can($user, $task->organization, Permission::TaskDelete);
    }
}
