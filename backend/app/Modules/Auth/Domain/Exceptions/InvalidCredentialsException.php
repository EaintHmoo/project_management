<?php

namespace App\Modules\Auth\Domain\Exceptions;

use Illuminate\Validation\ValidationException;

class InvalidCredentialsException extends ValidationException
{
    public static function make(): self
    {
        return static::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }
}
