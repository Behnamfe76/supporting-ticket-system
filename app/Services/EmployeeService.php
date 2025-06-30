<?php

namespace App\Services;

use App\Data\UserData;
use App\Models\User;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use App\Services\Contracts\EmployeeServiceInterface;
use App\Traits\MediaExtraInteractions;
use Exception;
use Illuminate\Support\Facades\DB;

class EmployeeService implements EmployeeServiceInterface
{
    use MediaExtraInteractions;

    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository,
    )
    {}

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->employeeRepository->index();
    }

    /**
     * @param UserData $data
     * @return User|Exception
     */
    public function store(UserData $data): User|Exception
    {
        try {
            DB::beginTransaction();
            $user = $this->employeeRepository->store($data);
            $user->assignRole($data->role);

            DB::commit();

            return $user;
        }catch(Exception $e){
            DB::rollBack();

            return $e;
        }
    }

    /**
     * @param UserData $data
     * @return User|Exception
     */
    public function update(UserData $data, User $user): User|Exception
    {
        try {
            DB::beginTransaction();
            $this->employeeRepository->update($data, $user);
            $user->syncRoles($data->role);

            DB::commit();

            return $user;
        }catch(Exception $e){
            DB::rollBack();

            return $e;
        }
    }
}
