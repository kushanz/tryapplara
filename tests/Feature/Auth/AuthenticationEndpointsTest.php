<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_a_user_and_returns_a_token(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password!123',
            'password_confirmation' => 'Password!123',
            'device_name' => 'phpunit',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'test@example.com')
            ->assertJsonPath('data.user.role', UserRole::Customer->value)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user',
                    'token',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => UserRole::Customer->value,
        ]);

        $token = PersonalAccessToken::query()->first();

        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->assertNotNull($token?->expires_at);
        $this->assertSame([], $token?->abilities ?? []);

        Notification::assertSentTo(
            User::query()->where('email', 'test@example.com')->firstOrFail(),
            VerifyEmail::class,
        );
    }

    public function test_login_returns_a_token_for_valid_credentials(): void
    {
        $user = User::factory()->manager()->create([
            'password' => 'Password!123',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'Password!123',
            'device_name' => 'phpunit',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.token', fn ($token) => is_string($token) && $token !== '')
            ->assertJsonMissingPath('data.user');

        $token = PersonalAccessToken::query()->first();

        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->assertNotNull($token?->expires_at);
        $this->assertSame(['users:read'], $token?->abilities ?? []);
    }

    public function test_me_returns_the_authenticated_user(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('data.user.email', $user->email)
            ->assertJsonPath('data.user.role', UserRole::Customer->value);
    }

    public function test_admin_login_receives_admin_token_abilities(): void
    {
        $user = User::factory()->admin()->create([
            'password' => 'Password!123',
        ]);

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'Password!123',
            'device_name' => 'phpunit',
        ])->assertOk();

        $token = PersonalAccessToken::query()->latest('id')->first();

        $this->assertSame(['users:read', 'users:update'], $token?->abilities ?? []);
    }

    public function test_logout_deletes_the_current_access_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('phpunit');

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_forgot_password_sends_a_reset_link_response(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->postJson('/api/forgot-password', [
            'email' => $user->email,
        ])
            ->assertOk()
            ->assertJsonPath('success', true);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_updates_the_user_password(): void
    {
        $user = User::factory()->create([
            'password' => 'OldPassword!123',
        ]);

        $token = Password::broker()->createToken($user);

        $this->postJson('/api/reset-password', [
            'email' => $user->email,
            'token' => $token,
            'password' => 'NewPassword!123',
            'password_confirmation' => 'NewPassword!123',
        ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $user->refresh();

        $this->assertTrue(Hash::check('NewPassword!123', $user->password));
    }

    public function test_email_verification_notification_can_be_resent(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/email/verification-notification')
            ->assertOk()
            ->assertJsonPath('success', true);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_verify_email_marks_the_user_as_verified(): void
    {
        $user = User::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ],
        );

        $this->getJson($url)
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', $user->email);

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
