<?php

namespace App\Services;

use App\Helpers\FilterHelper;
use App\Helpers\OrderHelper;
use App\Models\User;

class UserService {
    public function getData(array $payload)
    {
        $perPage = $payload['perPage'] ?? 10;
        $page = $payload['page'] ?? 1;

        $query = User::query();

        $query = FilterHelper::applyFilter($query, $payload);
        $query = OrderHelper::makeOrder($query, $payload);

        return $query
            ->paginate($perPage, ['*'], 'page', $page)
            ->through(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->getRoleNames()->first() ?? 'Sin rol'
                ];
            });
    }
}
