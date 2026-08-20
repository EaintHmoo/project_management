<?php

namespace App\Modules\Tasks\Presentation\Policies;

use App\Models\User;
use App\Modules\Tasks\Domain\Models\Label;
use App\Modules\Tenancy\Domain\Enums\Permission;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Domain\Support\OrganizationAccess;

class LabelPolicy
{
    public function viewAny(User $user, Organization $organization): bool
    {
        return OrganizationAccess::isMember($user, $organization);
    }

    public function manage(User $user, Organization $organization): bool
    {
        return OrganizationAccess::can($user, $organization, Permission::LabelManage);
    }

    public function delete(User $user, Label $label): bool
    {
        return OrganizationAccess::can($user, $label->organization, Permission::LabelManage);
    }
}
