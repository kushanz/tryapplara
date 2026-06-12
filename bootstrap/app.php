<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (\Illuminate\Validation\ValidationException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Validation failed',
                $exception->errors(),
                $exception->status,
            );
        });

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Unauthenticated',
                null,
                401,
            );
        });

        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                $exception->getMessage() ?: 'Forbidden',
                null,
                403,
            );
        });

        $exceptions->render(function (\Illuminate\Routing\Exceptions\InvalidSignatureException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Invalid or expired signed URL',
                null,
                403,
            );
        });

        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Too many requests',
                null,
                429,
            );
        });
    })->create();
