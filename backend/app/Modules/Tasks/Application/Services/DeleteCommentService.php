<?php

namespace App\Modules\Tasks\Application\Services;

use App\Modules\Tasks\Domain\Models\Comment;

final class DeleteCommentService
{
    public function execute(Comment $comment): void
    {
        $comment->delete();
    }
}
