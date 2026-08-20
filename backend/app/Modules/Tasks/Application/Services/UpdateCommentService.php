<?php

namespace App\Modules\Tasks\Application\Services;

use App\Modules\Tasks\Domain\Models\Comment;

final class UpdateCommentService
{
    public function execute(Comment $comment, string $body): Comment
    {
        $comment->update(['body' => $body]);

        return $comment;
    }
}
