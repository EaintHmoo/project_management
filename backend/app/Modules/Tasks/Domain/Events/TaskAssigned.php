<?php

namespace App\Modules\Tasks\Domain\Events;

use App\Modules\Tasks\Domain\Models\Task;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class TaskAssigned
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Task $task,
        public readonly int $assignedById,
    ) {}
}
