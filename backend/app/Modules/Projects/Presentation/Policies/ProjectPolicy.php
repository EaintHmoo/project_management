<?php

namespace App\Modules\Projects\Presentation\Policies;

use App\Models\User;
use App\Modules\Projects\Domain\Models\Project;
use App\Modules\Tenancy\Domain\Enums\Permission;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Domain\Support\OrganizationAccess;

class ProjectPolicy
{
    public function viewAny(User $user, Organization $organization): bool
    {
        return OrganizationAccess::isMember($user, $organization);
    }

    public function view(User $user, Project $project): bool
    {
        return OrganizationAccess::isMember($user, $project->organization);
    }

    public function create(User $user, Organization $organization): bool
    {
        return OrganizationAccess::can($user, $organization, Permission::ProjectCreate);
    }

    public function update(User $user, Project $project): bool
    {
        return OrganizationAccess::can($user, $project->organization, Permission::ProjectUpdate);
    }

    public function delete(User $user, Project $project): bool
    {
        return OrganizationAccess::can($user, $project->organization, Permission::ProjectDelete);
    }
}
