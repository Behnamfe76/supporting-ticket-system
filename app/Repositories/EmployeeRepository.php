<?php

namespace App\Repositories;

use App\Data\UserData;
use App\Models\User;
use App\Repositories\Contracts\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin')
                ->orWhere('name', '=', 'staff');
        })
            ->with('roles:name')
            ->select('id', 'slug', 'name', 'email', 'created_at')
            ->paginate(12);
    }

    /**
     * @param UserData $data
     * @return User
     */
    public function store(UserData $data): User
    {
        return User::create($data->toArray());
    }

    /**
     * @param UserData $data
     * @param User $user
     * @return bool
     */
    public function update(UserData $data, User $user): bool
    {
        $updateData = ['name' => $data->name];
        if (!empty($data->password)) {
            $updateData['password'] = $data->password;
        }
        return $user->update($updateData);
    }
}
