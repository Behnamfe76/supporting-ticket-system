<?php

namespace App\Repositories\Contracts;

use App\Data\UserData;
use App\Models\User;

interface EmployeeRepositoryInterface
{
    /**
     * @return mixed
     */
    public function index(): mixed;

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
