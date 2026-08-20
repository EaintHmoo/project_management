<?php

namespace App\Modules\Auth\Application\Services;

use App\Models\User;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Domain\Enums\SecurityEventType;
use App\Modules\Auth\Infrastructure\Security\SecurityEventRecorder;

final class VerifyEmailService
{
    public function __construct(
        private readonly SecurityEventRecorder $securityEvents,
    ) {}

    public function execute(User $user, RequestMetadata $requestMetadata): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->markEmailAsVerified();

        $this->securityEvents->record(SecurityEventType::EmailVerified, $requestMetadata, $user->id);
    }
}
