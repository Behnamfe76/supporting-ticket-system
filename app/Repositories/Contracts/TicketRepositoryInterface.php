<?php

namespace App\Repositories\Contracts;

use App\Data\TicketData;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface TicketRepositoryInterface
{
    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getUserTickets(Request $request): LengthAwarePaginator;


    /**
     * @param Request $request
     * @return mixed
     */
    public function userTicketCounts(Request $request): mixed;

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getTickets(Request $request): LengthAwarePaginator;


    /**
     * @param Request $request
     * @return mixed
     */
    public function ticketCounts(Request $request): mixed;

    /**
     * @param TicketData $data
     * @return Ticket
     */
    public function create(TicketData $data): Ticket;
}
