<?php

namespace App\Actions\Users;

use App\Services\Users\UserManagementService;

final readonly class CreateUserAction
{
    public function __construct(
        private UserManagementService $userManagementService,
    ) {
    }

    public function execute(array $data)
    {
        return $this->userManagementService->create($data);
    }
}
