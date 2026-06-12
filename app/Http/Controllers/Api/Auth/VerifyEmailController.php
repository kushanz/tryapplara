<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\VerifyEmailAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

final class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, VerifyEmailAction $action)
    {
        $user = $action->execute(
            $request->route('id'),
            (string) $request->route('hash'),
        );

        return ApiResponse::success([
            'user' => new UserResource($user),
        ], 'Email verified successfully');
    }
}
