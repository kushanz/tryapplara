<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\SendEmailVerificationNotificationAction;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

final class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request, SendEmailVerificationNotificationAction $action)
    {
        $action->execute($request->user());

        return ApiResponse::success(null, 'Email verification link sent successfully');
    }
}
