<?php

namespace App\Modules\Tasks\Application\Services;

use App\Modules\Tasks\Domain\Events\UserMentionedInComment;
use App\Modules\Tasks\Domain\Models\Comment;
use App\Modules\Tasks\Domain\Models\Task;

final class CreateCommentService
{
    /**
     * @param  list<int>  $mentions
     */
    public function execute(Task $task, int $userId, string $body, array $mentions = [], ?int $parentId = null): Comment
    {
        $comment = $task->comments()->create([
            'user_id' => $userId,
            'parent_id' => $parentId,
            'body' => $body,
            'mentions' => $mentions,
        ]);

        $mentionedUserIds = array_values(array_diff($mentions, [$userId]));

        if ($mentionedUserIds !== []) {
            UserMentionedInComment::dispatch($comment, $mentionedUserIds);
        }

        return $comment;
    }
}
