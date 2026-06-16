<?php

namespace App\Actions\Users;

use App\Models\User;

final readonly class ShowUserAction
{
    public function execute(User $user): User
    {
        return $user;
    }
}
