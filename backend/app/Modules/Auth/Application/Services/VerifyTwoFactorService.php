<?php

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Domain\Enums\SecurityEventType;
use App\Modules\Auth\Domain\Models\TwoFactorChallenge;
use App\Modules\Auth\Infrastructure\Security\SecurityEventRecorder;
use App\Modules\Auth\Infrastructure\TwoFactor\EmailOtpProvider;
use Laravel\Sanctum\NewAccessToken;

final class VerifyTwoFactorService
{
    public function __construct(
        private readonly EmailOtpProvider $otp,
        private readonly SecurityEventRecorder $securityEvents,
    ) {}

    public function execute(TwoFactorChallenge $challenge, string $code, RequestMetadata $requestMetadata): NewAccessToken
    {
        try {
            $this->otp->verify($challenge, $code);
        } catch (\Throwable $e) {
            $this->securityEvents->record(SecurityEventType::TwoFactorFailed, $requestMetadata, $challenge->user_id);

            throw $e;
        }

        $user = $challenge->user;

        $this->securityEvents->record(SecurityEventType::TwoFactorVerified, $requestMetadata, $user->id);
        $this->securityEvents->record(SecurityEventType::LoginSuccess, $requestMetadata, $user->id);

        return $user->createToken('api', expiresAt: now()->addDays(30));
    }
}
