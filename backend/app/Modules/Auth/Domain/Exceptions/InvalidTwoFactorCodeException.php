<?php

namespace App\Modules\Auth\Domain\Exceptions;

use Illuminate\Validation\ValidationException;

class InvalidTwoFactorCodeException extends ValidationException
{
    public static function make(string $message = 'The verification code is invalid or has expired.'): self
    {
        return static::withMessages(['code' => $message]);
    }
}
