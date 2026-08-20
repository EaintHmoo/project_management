<?php

namespace App\Modules\Tasks\Presentation\Policies;

use App\Models\User;
use App\Modules\Tasks\Domain\Models\Comment;
use App\Modules\Tasks\Domain\Models\Task;
use App\Modules\Tenancy\Domain\Enums\Permission;
use App\Modules\Tenancy\Domain\Support\OrganizationAccess;

class CommentPolicy
{
    public function create(User $user, Task $task): bool
    {
        return OrganizationAccess::isMember($user, $task->organization);
    }

    public function update(User $user, Comment $comment): bool
    {
        return $comment->user_id === $user->id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($comment->user_id === $user->id) {
            return true;
        }

        return OrganizationAccess::can($user, $comment->task->organization, Permission::TaskDelete);
    }
}
