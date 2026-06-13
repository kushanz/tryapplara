<?php

namespace App\Services\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class AuthService
{
    public function register(array $data): array
    {
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => UserRole::Customer,
        ]);

        $user->sendEmailVerificationNotification();

        return $this->issueTokenPayload($user, $data['device_name'] ?? null);
    }

    public function login(array $data): array
    {
        $user = User::query()->where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->issueTokenPayload($user, $data['device_name'] ?? null);
    }

    public function logout(User $user): void
    {
        $currentToken = $user->currentAccessToken();

        if ($currentToken) {
            $currentToken->delete();

            return;
        }

        $user->tokens()->delete();
    }

    public function sendPasswordResetLink(array $data): void
    {
        Password::sendResetLink([
            'email' => $data['email'],
        ]);
    }

    public function resetPassword(array $data): void
    {
        $status = Password::reset(
            $data,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        }
    }

    public function sendEmailVerificationNotification(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            throw new AuthorizationException('Email is already verified.');
        }

        $user->sendEmailVerificationNotification();
    }

    public function verifyEmail(int|string $id, string $hash): User
    {
        $user = User::query()->findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException('Invalid email verification link.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        return $user;
    }

    private function issueTokenPayload(User $user, ?string $deviceName = null): array
    {
        $expirationMinutes = config('sanctum.expiration');
        $expiresAt = is_numeric($expirationMinutes)
            ? Carbon::now()->addMinutes((int) $expirationMinutes)
            : null;

        $token = $user->createToken(
            $deviceName ?: 'api-token',
            $user->tokenAbilities(),
            $expiresAt,
        )->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
