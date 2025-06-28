<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Data\TicketData;
use App\Enums\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\TicketRepositoryInterface;

class TicketRepository implements TicketRepositoryInterface
{
    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getUserTickets(Request $request): LengthAwarePaginator
    {
        $userId = $request->user()->id;

        return Ticket::select('subject', 'priority', 'status', 'created_at')
        ->when($request->has('status'), function ($query) use ($request) {
                $status = TicketStatus::fromString($request->status);

                if($status instanceof TicketStatus){
                    $query->where('status', $status->value);
                }
            })
            ->whereHas('replies', function ($query) use ($userId) {
                $query->where('author_id', $userId);
            })
            ->paginate(10);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function ticketCounts(Request $request): mixed
    {
        $userId = $request->user()->id;

        return Ticket::whereHas('replies', function ($query) use ($userId) {
            $query->where('author_id', $userId);
        })
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
    }

    /**
     * @param TicketData $data
     * @return Ticket
     */
    public function create(TicketData $data): Ticket
    {
        return Ticket::create($data->toArray());
    }
}
