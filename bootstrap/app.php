<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $exceptions->render(function (AccessDeniedHttpException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'This action is unauthorized.',
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

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Resource not found',
                null,
                404,
            );
        });

        $exceptions->render(function (HttpResponseException $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return $exception->getResponse();
        });

        $exceptions->render(function (HttpExceptionInterface $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                $exception->getMessage() !== '' ? $exception->getMessage() : 'Request failed',
                null,
                $exception->getStatusCode(),
            );
        });

        $exceptions->render(function (\Throwable $exception, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return \App\Support\ApiResponse::error(
                'Server error',
                null,
                500,
            );
        });
    })->create();
