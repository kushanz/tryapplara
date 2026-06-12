<?php

namespace App\Actions\Auth;

use App\Services\Auth\AuthService;

final readonly class ResetPasswordAction
{
    public function __construct(
        private AuthService $authService,
    ) {
    }

    public function execute(array $data): void
    {
        $this->authService->resetPassword($data);
    }
}
