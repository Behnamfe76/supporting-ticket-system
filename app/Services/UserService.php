<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Traits\MediaExtraInteractions;

class UserService implements UserServiceInterface
{
    use MediaExtraInteractions;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
    )
    {}

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->userRepository->Index();
    }
}
