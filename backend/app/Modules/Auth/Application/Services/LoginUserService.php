<?php

namespace App\Modules\Auth\Application\Services;

use App\Models\User;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Domain\Enums\SecurityEventType;
use App\Modules\Auth\Domain\Exceptions\InvalidCredentialsException;
use App\Modules\Auth\Domain\Models\TwoFactorChallenge;
use App\Modules\Auth\Infrastructure\Security\SecurityEventRecorder;
use App\Modules\Auth\Infrastructure\TwoFactor\EmailOtpProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

final class LoginUserService
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 60;

    public function __construct(
        private readonly EmailOtpProvider $otp,
        private readonly SecurityEventRecorder $securityEvents,
    ) {}

    public function execute(string $email, string $password, RequestMetadata $requestMetadata): TwoFactorChallenge
    {
        $throttleKey = "login:{$email}:{$requestMetadata->ipAddress}";

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again in '.RateLimiter::availableIn($throttleKey).' seconds.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

            $this->securityEvents->record(SecurityEventType::LoginFailed, $requestMetadata, $user?->id, metadata: ['email' => $email]);

            throw InvalidCredentialsException::make();
        }

        RateLimiter::clear($throttleKey);

        $this->securityEvents->record(SecurityEventType::TwoFactorSent, $requestMetadata, $user->id);

        return $this->otp->issue($user);
    }
}
