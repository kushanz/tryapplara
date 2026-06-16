<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Services\Users\UserManagementService;

final readonly class UpdateUserAction
{
    public function __construct(
        private UserManagementService $userManagementService,
    ) {
    }

    public function execute(User $user, array $data): User
    {
        return $this->userManagementService->update($user, $data);
    }
}
