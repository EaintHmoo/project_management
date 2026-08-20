<?php

namespace App\Modules\Tasks\Domain\DTOs;

use App\Modules\Tasks\Domain\Enums\TaskPriority;

final class CreateTaskData
{
    public function __construct(
        public readonly int $organizationId,
        public readonly int $projectId,
        public readonly ?int $assigneeId,
        public readonly int $reporterId,
        public readonly string $title,
        public readonly ?string $description,
        public readonly TaskPriority $priority,
        public readonly ?string $dueAt,
        /** @var list<int> */
        public readonly array $labelIds = [],
    ) {}
}
