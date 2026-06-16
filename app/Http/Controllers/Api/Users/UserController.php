<?php

namespace App\Http\Controllers\Api\Users;

use App\Actions\Users\CreateUserAction;
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\ListUsersAction;
use App\Actions\Users\ShowUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\IndexUsersRequest;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Support\ApiResponse;

final class UserController extends Controller
{
    public function index(IndexUsersRequest $request, ListUsersAction $action)
    {
        $users = $action->execute(
            (int) ($request->validated('per_page') ?? 15),
            $request->validated('search'),
        );

        return ApiResponse::success(
            UserResource::collection($users->getCollection())->resolve(),
            'Users fetched successfully',
            200,
            [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'search' => $request->validated('search'),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ],
        );
    }

    public function store(StoreUserRequest $request, CreateUserAction $action)
    {
        $user = $action->execute($request->validated());

        return ApiResponse::success(
            new UserResource($user),
            'User created successfully',
            201,
        );
    }

    public function show(User $user, ShowUserAction $action)
    {
        $this->authorize('view', $user);

        return ApiResponse::success(
            new UserResource($action->execute($user)),
            'User fetched successfully',
        );
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action)
    {
        $updatedUser = $action->execute($user, $request->validated());

        return ApiResponse::success(
            new UserResource($updatedUser),
            'User updated successfully',
        );
    }

    public function destroy(User $user, DeleteUserAction $action)
    {
        $this->authorize('delete', $user);

        $action->execute($user);

        return ApiResponse::success(null, 'User deleted successfully');
    }
}
