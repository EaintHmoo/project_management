<?php

namespace App\Modules\Meetings\Presentation\Policies;

use App\Models\User;
use App\Modules\Meetings\Domain\Models\Meeting;
use App\Modules\Tenancy\Domain\Enums\Permission;
use App\Modules\Tenancy\Domain\Models\Organization;
use App\Modules\Tenancy\Domain\Support\OrganizationAccess;

class MeetingPolicy
{
    public function viewAny(User $user, Organization $organization): bool
    {
        return OrganizationAccess::isMember($user, $organization);
    }

    public function view(User $user, Meeting $meeting): bool
    {
        return OrganizationAccess::isMember($user, $meeting->organization);
    }

    public function create(User $user, Organization $organization): bool
    {
        return OrganizationAccess::can($user, $organization, Permission::MeetingCreate);
    }

    public function update(User $user, Meeting $meeting): bool
    {
        return OrganizationAccess::can($user, $meeting->organization, Permission::MeetingUpdate);
    }

    public function cancel(User $user, Meeting $meeting): bool
    {
        return OrganizationAccess::can($user, $meeting->organization, Permission::MeetingCancel);
    }

    public function delete(User $user, Meeting $meeting): bool
    {
        return OrganizationAccess::can($user, $meeting->organization, Permission::MeetingDelete);
    }

    public function respond(User $user, Meeting $meeting): bool
    {
        return $meeting->participants()->where('user_id', $user->id)->exists();
    }
}
