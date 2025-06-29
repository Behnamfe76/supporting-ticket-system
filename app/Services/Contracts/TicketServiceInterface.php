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

interface TicketServiceInterface
{

    /**
     * @param Request $request
     * @return array
     */
    public function getUserTickets(Request $request): array;

    /**
     * @param TicketData $data
     * @return Ticket|Throwable|Exception
     */
    public function createTicket(TicketData $data): Ticket|Throwable|Exception;

    /**
     * @param Request $request
     * @return array
     */
    public function getAdminTickets(Request $request): array;

    /**
     * @param StoreReplyRequest $request
     * @param ReplyData $data
     * @return bool|Throwable|Exception
     */
    public function replyTicket(StoreReplyRequest $request, ReplyData $data): bool|Throwable|Exception;
}
