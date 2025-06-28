<?php

namespace App\Services;

use App\Data\ReplyData;
use App\Data\TicketData;
use App\Models\Ticket;
use App\Repositories\Contracts\ReplyRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Services\Contracts\TicketServiceInterface;
use App\Traits\MediaExtraInteractions;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class TicketService implements TicketServiceInterface
{
    use MediaExtraInteractions;

    public function __construct(
        protected TicketRepositoryInterface $ticketRepository,
        protected ReplyRepositoryInterface $replyRepository
    ) {}

    /**
     * @param TicketData $data
     * @return Ticket|Throwable|Exception
     */
    public function createTicket(TicketData $data): Ticket|Throwable|Exception
    {
        try {
            DB::beginTransaction();
            $ticket = $this->ticketRepository->create($data);
            $replyData = ReplyData::new($data, $ticket);
            $reply = $this->replyRepository->create($replyData);

            if($replyData->has_attachments) {
                $this->cleanupTemporaryFile($reply, $data->fileData);
            }
            DB::commit();

            return $ticket;
        } catch (\Throwable $th) {
            DB::rollback();

            return $th;
        }
    }
}
