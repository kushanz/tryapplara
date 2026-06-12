<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\SendPasswordResetLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Support\ApiResponse;

final class ForgotPasswordController extends Controller
{
    public function store(ForgotPasswordRequest $request, SendPasswordResetLinkAction $action)
    {
        $action->execute($request->validated());

        return ApiResponse::success(
            null,
            'If the account exists, a password reset link has been sent.',
        );
    }
}
