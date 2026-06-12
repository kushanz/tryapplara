<?php

namespace App\Actions\Auth;

use App\Services\Auth\AuthService;

final readonly class RegisterUserAction
{
    public function __construct(
        private AuthService $authService,
    ) {
    }

    public function execute(array $data): array
    {
        return $this->authService->register($data);
    }
}
