<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

final class UserPolicy
{
    public function viewAny(User $authUser): bool
    {
        return $this->canReadUsers($authUser);
    }

    public function view(User $authUser, User $targetUser): bool
    {
        return $this->canReadUsers($authUser);
    }

    public function create(User $authUser): bool
    {
        return $this->canManageUsers($authUser);
    }

    public function update(User $authUser, User $targetUser): bool
    {
        return $this->canManageUsers($authUser);
    }

    public function delete(User $authUser, User $targetUser): bool
    {
        return $this->canManageUsers($authUser)
            && $authUser->id !== $targetUser->id;
    }

    private function canReadUsers(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager], true)
            && $user->tokenCan('users:read');
    }

    private function canManageUsers(User $user): bool
    {
        return $user->hasRole(UserRole::Admin)
            && $user->tokenCan('users:update');
    }
}
