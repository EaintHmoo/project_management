<?php

namespace App\Modules\Auth\Application\Services;

use Illuminate\Support\Facades\Password;

final class ForgotPasswordService
{
    public function execute(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }
}
