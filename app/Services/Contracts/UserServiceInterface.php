<?php

namespace App\Services\Contracts;

use App\Data\ReplyData;
use App\Data\TicketData;
use App\Data\UserData;
use App\Http\Requests\Tickets\StoreReplyRequest;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

interface UserServiceInterface
{
    /**
     * @return mixed
     */
    public function index(): mixed;

    /**
     * @param UserData $data
     * @return User|Exception
     */
    public function store(UserData $data): User|Exception;

    /**
     * @param UserData $data
     * @param User $user
     * @return bool
     */
    public function update(UserData $data, User $user): bool;
}
