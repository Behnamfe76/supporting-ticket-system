<?php

namespace App\Services\Contracts;

use App\Data\TicketData;
use App\Models\Ticket;
use Exception;
use Throwable;

interface TicketServiceInterface
{
    public function createTicket(TicketData $data): Ticket|Throwable|Exception;
}
