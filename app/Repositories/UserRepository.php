<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return User::whereHas('roles',  function ($query) {
            $query->where('name', '=', 'user');
        })
            ->select('slug', 'name', 'email', 'created_at')
            ->paginate(12);
    }
}
