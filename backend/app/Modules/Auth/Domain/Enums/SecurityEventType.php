<?php

namespace App\Modules\Auth\Domain\Enums;

enum SecurityEventType: string
{
    case Registered = 'registered';
    case LoginSuccess = 'login_success';
    case LoginFailed = 'login_failed';
    case Logout = 'logout';
    case TwoFactorSent = 'two_factor_sent';
    case TwoFactorVerified = 'two_factor_verified';
    case TwoFactorFailed = 'two_factor_failed';
    case EmailVerified = 'email_verified';
    case PasswordChanged = 'password_changed';
    case PasswordReset = 'password_reset';
}
