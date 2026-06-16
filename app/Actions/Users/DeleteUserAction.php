<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Services\Users\UserManagementService;

final readonly class DeleteUserAction
{
    public function __construct(
        private UserManagementService $userManagementService,
    ) {
    }

    public function execute(User $user): void
    {
        $this->userManagementService->delete($user);
    }
}
