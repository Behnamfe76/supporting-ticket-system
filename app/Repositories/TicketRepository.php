<?php

namespace App\Repositories;

use App\Data\TicketData;
use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;

class TicketRepository implements TicketRepositoryInterface
{
    /**
     * @param TicketData $data
     * @return Ticket
     */
    public function create(TicketData $data): Ticket
    {
        return Ticket::create($data->toArray());
    }
}
