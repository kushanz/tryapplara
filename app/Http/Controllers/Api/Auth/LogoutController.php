<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\LogoutUserAction;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

final class LogoutController extends Controller
{
    public function store(Request $request, LogoutUserAction $action)
    {
        $action->execute($request->user());

        return ApiResponse::success(null, 'Logout successful');
    }
}
