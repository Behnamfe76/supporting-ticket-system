<?php

namespace App\Repositories\Contracts;

use App\Data\TicketData;
use App\Models\Ticket;

interface TicketRepositoryInterface
{
    /**
     * @param TicketData $data
     * @return Ticket
     */
    public function create(TicketData $data): Ticket;
}
