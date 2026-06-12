<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\ResetPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Support\ApiResponse;

final class ResetPasswordController extends Controller
{
    public function store(ResetPasswordRequest $request, ResetPasswordAction $action)
    {
        $action->execute($request->validated());

        return ApiResponse::success(null, 'Password reset successful');
    }
}
