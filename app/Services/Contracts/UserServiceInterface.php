<?php

namespace App\Services\Contracts;

use App\Data\ReplyData;
use App\Data\TicketData;
use App\Http\Requests\Tickets\StoreReplyRequest;
use App\Models\Ticket;
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
}
