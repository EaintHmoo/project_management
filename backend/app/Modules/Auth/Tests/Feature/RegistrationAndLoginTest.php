<?php

namespace App\Modules\Auth\Tests\Feature;

use App\Models\User;
use App\Modules\Auth\Infrastructure\TwoFactor\Notifications\TwoFactorCodeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationAndLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_and_receives_an_email_verification_notification(): void
    {
        Notification::fake();

        $this->postJson('/api/auth/register', [
            'name' => 'Alice Morgan',
            'email' => 'alice@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', 'alice@example.com');

        $this->assertDatabaseHas('users', ['email' => 'alice@example.com']);
        $this->assertDatabaseHas('security_events', ['type' => 'registered']);
    }

    public function test_login_issues_a_two_factor_challenge_instead_of_a_token(): void
    {
        Notification::fake();

        $user = User::factory()->create(['password' => Hash::make('Password123!')]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['challenge_id', 'expires_at']]);

        $this->assertDatabaseHas('two_factor_challenges', ['user_id' => $user->id]);
        $this->assertDatabaseHas('security_events', ['user_id' => $user->id, 'type' => 'two_factor_sent']);
    }

    public function test_login_fails_with_invalid_credentials_and_records_a_security_event(): void
    {
        $user = User::factory()->create(['password' => Hash::make('Password123!')]);

        $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertStatus(422);

        $this->assertDatabaseHas('security_events', ['user_id' => $user->id, 'type' => 'login_failed']);
    }

    public function test_the_full_login_and_two_factor_flow_issues_an_api_token(): void
    {
        Notification::fake();

        $user = User::factory()->create(['password' => Hash::make('Password123!')]);

        $challengeId = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->json('data.challenge_id');

        $code = null;
        Notification::assertSentTo($user, TwoFactorCodeNotification::class, function (TwoFactorCodeNotification $notification) use (&$code) {
            $code = $notification->code;

            return true;
        });

        $response = $this->postJson('/api/auth/two-factor/verify', [
            'challenge_id' => $challengeId,
            'code' => $code,
        ])->assertOk()
            ->assertJsonStructure(['data' => ['user', 'token']]);

        $token = $response->json('data.token');

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('email', $user->email);
    }
}
