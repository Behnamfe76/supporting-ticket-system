<?php

namespace App\Repositories\Contracts;

use App\Data\UserData;
use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @return mixed
     */
    public function Index(): mixed;

    /**
     * @param UserData $data
     * @return User
     */
    public function store(UserData $data): User;

    /**
     * @param UserData $data
     * @param User $user
     * @return bool
     */
    public function update(UserData $data, User $user): bool;
}
