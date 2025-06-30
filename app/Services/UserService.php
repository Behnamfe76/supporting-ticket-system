<?php

namespace App\Services;

use App\Data\UserData;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Traits\MediaExtraInteractions;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService implements UserServiceInterface
{
    use MediaExtraInteractions;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
    )
    {
    }

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->userRepository->Index();
    }


    /**
     * @param UserData $data
     * @return User|Exception
     */
    public function store(UserData $data): User|Exception
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->store($data);
            $user->assignRole('user');
            DB::commit();

            return $user;
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());

            return $e;
        }
    }

    /**
     * @param UserData $data
     * @param User $user
     * @return bool
     */
    public function update(UserData $data, User $user): bool
    {
        return $this->userRepository->update($data, $user);
    }
}
