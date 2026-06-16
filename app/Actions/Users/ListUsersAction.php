<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListUsersAction
{
    public function execute(int $perPage, ?string $search = null): LengthAwarePaginator
    {
        return User::query()
            ->when($search, function ($query, string $searchTerm) {
                $query->where(function ($subQuery) use ($searchTerm): void {
                    $subQuery
                        ->where('name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('email', 'like', '%'.$searchTerm.'%');
                });
            })
            ->latest('id')
            ->paginate($perPage);
    }
}
