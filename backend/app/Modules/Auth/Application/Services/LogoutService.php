<?php

namespace App\Modules\Auth\Application\Services;

use App\Models\User;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Domain\Enums\SecurityEventType;
use App\Modules\Auth\Infrastructure\Security\SecurityEventRecorder;
use Laravel\Sanctum\PersonalAccessToken;

final class LogoutService
{
    public function __construct(
        private readonly SecurityEventRecorder $securityEvents,
    ) {}

    public function execute(User $user, RequestMetadata $requestMetadata): void
    {
        /** @var PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();
        $token?->delete();

        $this->securityEvents->record(SecurityEventType::Logout, $requestMetadata, $user->id);
    }
}
