<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Services\Auth\AuthService;

final readonly class SendEmailVerificationNotificationAction
{
    public function __construct(
        private AuthService $authService,
    ) {
    }

    public function execute(User $user): void
    {
        $this->authService->sendEmailVerificationNotification($user);
    }
}
