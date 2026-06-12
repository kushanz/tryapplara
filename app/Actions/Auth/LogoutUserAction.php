<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Services\Auth\AuthService;

final readonly class LogoutUserAction
{
    public function __construct(
        private AuthService $authService,
    ) {
    }

    public function execute(User $user): void
    {
        $this->authService->logout($user);
    }
}
