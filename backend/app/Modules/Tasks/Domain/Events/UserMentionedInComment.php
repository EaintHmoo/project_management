<?php

namespace App\Modules\Tasks\Domain\Events;

use App\Modules\Tasks\Domain\Models\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class UserMentionedInComment
{
    use Dispatchable, SerializesModels;

    /**
     * @param  list<int>  $mentionedUserIds
     */
    public function __construct(
        public readonly Comment $comment,
        public readonly array $mentionedUserIds,
    ) {}
}
