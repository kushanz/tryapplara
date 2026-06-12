<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(
                sprintf('%s|%s', $request->ip(), (string) $request->input('email'))
            );
        });

        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(3)->by(
                sprintf('%s|%s', $request->ip(), (string) $request->input('email'))
            );
        });

        RateLimiter::for('verification-notification', function (Request $request) {
            $userKey = (string) optional($request->user())->getAuthIdentifier();

            return Limit::perMinute(3)->by($userKey !== '' ? $userKey : $request->ip());
        });

        RateLimiter::for('verification-link', function (Request $request) {
            return Limit::perMinute(6)->by((string) $request->route('id'));
        });

        ResetPassword::createUrlUsing(function (object $user, string $token): string {
            $frontendUrl = rtrim((string) config('app.frontend_url'), '/');

            return sprintf(
                '%s/reset-password?token=%s&email=%s',
                $frontendUrl,
                $token,
                urlencode($user->getEmailForPasswordReset()),
            );
        });
    }
}
