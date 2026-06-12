<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

final class AuthUserController extends Controller
{
    public function show(Request $request)
    {
        return ApiResponse::success([
            'user' => new UserResource($request->user()),
        ], 'Authenticated user fetched successfully');
    }
}
