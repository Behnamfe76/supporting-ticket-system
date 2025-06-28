<?php

namespace App\Services\Contracts;

use App\Data\TicketData;
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
}
