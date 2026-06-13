<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Customer = 'customer';

    public function tokenAbilities(): array
    {
        return match ($this) {
            self::Admin => ['users:read', 'users:update'],
            self::Manager => ['users:read'],
            self::Customer => [],
        };
    }
}
