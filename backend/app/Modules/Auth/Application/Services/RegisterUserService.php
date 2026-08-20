<?php

namespace App\Modules\Auth\Application\Services;

use App\Models\User;
use App\Modules\Auth\Domain\DTOs\RegisterUserData;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Domain\Enums\SecurityEventType;
use App\Modules\Auth\Infrastructure\Security\SecurityEventRecorder;
use Illuminate\Support\Facades\Hash;

final class RegisterUserService
{
    public function __construct(
        private readonly SecurityEventRecorder $securityEvents,
    ) {}

    public function execute(RegisterUserData $data, RequestMetadata $requestMetadata): User
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        $user->sendEmailVerificationNotification();

        $this->securityEvents->record(SecurityEventType::Registered, $requestMetadata, $user->id);

        return $user;
    }
}
