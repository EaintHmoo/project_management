<?php

namespace App\Modules\Auth\Infrastructure\TwoFactor;

use App\Models\User;
use App\Modules\Auth\Domain\Exceptions\InvalidTwoFactorCodeException;
use App\Modules\Auth\Domain\Exceptions\TwoFactorResendCooldownException;
use App\Modules\Auth\Domain\Models\TwoFactorChallenge;
use App\Modules\Auth\Infrastructure\TwoFactor\Notifications\TwoFactorCodeNotification;
use Illuminate\Support\Facades\Hash;

class EmailOtpProvider
{
    private const CODE_LENGTH = 6;

    private const EXPIRY_MINUTES = 5;

    private const MAX_ATTEMPTS = 5;

    private const RESEND_COOLDOWN_SECONDS = 60;

    public function issue(User $user): TwoFactorChallenge
    {
        $lastChallenge = TwoFactorChallenge::where('user_id', $user->id)->latest('id')->first();

        if ($lastChallenge && $lastChallenge->created_at->diffInSeconds(now()) < self::RESEND_COOLDOWN_SECONDS) {
            throw TwoFactorResendCooldownException::make(
                self::RESEND_COOLDOWN_SECONDS - (int) $lastChallenge->created_at->diffInSeconds(now()),
            );
        }

        $code = (string) random_int(100000, 999999);

        $challenge = TwoFactorChallenge::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
            'attempts' => 0,
        ]);

        $user->notify(new TwoFactorCodeNotification($code, self::EXPIRY_MINUTES));

        return $challenge;
    }

    public function verify(TwoFactorChallenge $challenge, string $code): void
    {
        if ($challenge->isVerified() || $challenge->isExpired()) {
            throw InvalidTwoFactorCodeException::make();
        }

        if ($challenge->attempts >= self::MAX_ATTEMPTS) {
            throw InvalidTwoFactorCodeException::make('Too many attempts. Please request a new code.');
        }

        if (! Hash::check($code, $challenge->code_hash)) {
            $challenge->increment('attempts');

            throw InvalidTwoFactorCodeException::make();
        }

        $challenge->update(['verified_at' => now()]);
    }
}
