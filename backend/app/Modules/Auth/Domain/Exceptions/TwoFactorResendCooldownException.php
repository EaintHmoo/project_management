<?php

namespace App\Modules\Auth\Domain\Exceptions;

use Illuminate\Validation\ValidationException;

class TwoFactorResendCooldownException extends ValidationException
{
    public static function make(int $secondsRemaining): self
    {
        return static::withMessages([
            'code' => "Please wait {$secondsRemaining} more second(s) before requesting a new code.",
        ]);
    }
}
