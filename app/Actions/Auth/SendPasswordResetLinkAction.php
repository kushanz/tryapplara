<?php

namespace App\Actions\Auth;

use App\Services\Auth\AuthService;

final readonly class SendPasswordResetLinkAction
{
    public function __construct(
        private AuthService $authService,
    ) {
    }

    public function execute(array $data): void
    {
        $this->authService->sendPasswordResetLink($data);
    }
}
