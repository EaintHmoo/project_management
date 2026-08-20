<?php

namespace App\Modules\Auth\Tests\Unit;

use App\Models\User;
use App\Modules\Auth\Domain\Exceptions\InvalidTwoFactorCodeException;
use App\Modules\Auth\Domain\Models\TwoFactorChallenge;
use App\Modules\Auth\Infrastructure\TwoFactor\EmailOtpProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailOtpProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_rejects_an_incorrect_code_and_increments_attempts(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $challenge = TwoFactorChallenge::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $provider = new EmailOtpProvider;

        $this->expectException(InvalidTwoFactorCodeException::class);

        try {
            $provider->verify($challenge, '000000');
        } finally {
            $this->assertSame(1, $challenge->fresh()->attempts);
        }
    }

    public function test_verify_rejects_an_expired_challenge(): void
    {
        $user = User::factory()->create();
        $challenge = TwoFactorChallenge::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make('123456'),
            'expires_at' => now()->subMinute(),
            'attempts' => 0,
        ]);

        $provider = new EmailOtpProvider;

        $this->expectException(InvalidTwoFactorCodeException::class);

        $provider->verify($challenge, '123456');
    }

    public function test_verify_accepts_the_correct_code(): void
    {
        $user = User::factory()->create();
        $challenge = TwoFactorChallenge::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        (new EmailOtpProvider)->verify($challenge, '123456');

        $this->assertNotNull($challenge->fresh()->verified_at);
    }
}
