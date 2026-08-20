<?php

namespace App\Modules\Tasks\Application\Services;

use App\Modules\Tasks\Domain\Models\Comment;
use App\Modules\Tasks\Domain\Models\Task;

final class CreateCommentService
{
    /**
     * @param  list<int>  $mentions
     */
    public function execute(Task $task, int $userId, string $body, array $mentions = [], ?int $parentId = null): Comment
    {
        return $task->comments()->create([
            'user_id' => $userId,
            'parent_id' => $parentId,
            'body' => $body,
            'mentions' => $mentions,
        ]);
    }
}
