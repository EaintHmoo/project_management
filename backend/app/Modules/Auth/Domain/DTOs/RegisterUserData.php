<?php

namespace App\Modules\Auth\Domain\DTOs;

final class RegisterUserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {}
}
