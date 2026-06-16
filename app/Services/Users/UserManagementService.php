<?php

namespace App\Services\Users;

use App\Models\User;

final class UserManagementService
{
    public function create(array $data): User
    {
        return User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => $data['password'],
        ]);
    }

    public function update(User $user, array $data): User
    {
        $updateData = [];

        if (array_key_exists('name', $data)) {
            $updateData['name'] = $data['name'];
        }

        if (array_key_exists('email', $data)) {
            $updateData['email'] = $data['email'];

            if ($data['email'] !== $user->email) {
                $updateData['email_verified_at'] = null;
            }
        }

        if (array_key_exists('role', $data)) {
            $updateData['role'] = $data['role'];
        }

        if (array_key_exists('password', $data) && $data['password']) {
            $updateData['password'] = $data['password'];
        }

        $user->fill($updateData);
        $user->save();

        return $user->refresh();
    }

    public function delete(User $user): void
    {
        $user->tokens()->delete();
        $user->delete();
    }
}
