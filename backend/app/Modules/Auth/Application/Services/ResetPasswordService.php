<?php

namespace App\Modules\Auth\Application\Services;

use App\Models\User;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Domain\Enums\SecurityEventType;
use App\Modules\Auth\Infrastructure\Security\SecurityEventRecorder;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

final class ResetPasswordService
{
    public function __construct(
        private readonly SecurityEventRecorder $securityEvents,
    ) {}

    public function execute(string $email, string $token, string $password, RequestMetadata $requestMetadata): string
    {
        $status = Password::reset(
            ['email' => $email, 'token' => $token, 'password' => $password, 'password_confirmation' => $password],
            function (User $user) use ($password, $requestMetadata) {
                $user->forceFill(['password' => Hash::make($password)])->save();

                event(new PasswordReset($user));

                $this->securityEvents->record(SecurityEventType::PasswordReset, $requestMetadata, $user->id);
            },
        );

        return $status;
    }
}
