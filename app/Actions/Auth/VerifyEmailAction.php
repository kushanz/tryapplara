<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Services\Auth\AuthService;

final readonly class VerifyEmailAction
{
    public function __construct(
        private AuthService $authService,
    ) {
    }

    public function execute(int|string $id, string $hash): User
    {
        return $this->authService->verifyEmail($id, $hash);
    }
}
