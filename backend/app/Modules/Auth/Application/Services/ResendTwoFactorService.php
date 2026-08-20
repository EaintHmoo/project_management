<?php

namespace App\Modules\Auth\Application\Services;

use App\Models\User;
use App\Modules\Auth\Domain\Models\TwoFactorChallenge;
use App\Modules\Auth\Infrastructure\TwoFactor\EmailOtpProvider;

final class ResendTwoFactorService
{
    public function __construct(
        private readonly EmailOtpProvider $otp,
    ) {}

    public function execute(User $user): TwoFactorChallenge
    {
        return $this->otp->issue($user);
    }
}
