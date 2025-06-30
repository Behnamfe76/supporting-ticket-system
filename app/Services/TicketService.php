<?php

namespace App\Services;

use Exception;
use Throwable;
use App\Models\Ticket;
use App\Data\ReplyData;
use App\Data\TicketData;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Data\UploadingFileData;
use Illuminate\Support\Facades\DB;
use App\Events\CreateNewTicketEvent;
use App\Traits\MediaExtraInteractions;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Tickets\StoreReplyRequest;
use App\Services\Contracts\TicketServiceInterface;
use App\Repositories\Contracts\ReplyRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;

class TicketService implements TicketServiceInterface
{
    use MediaExtraInteractions;

    public function __construct(
        protected TicketRepositoryInterface $ticketRepository,
        protected ReplyRepositoryInterface  $replyRepository
    )
    {}

    /**@param Request $request
     * @return array{tickets: LengthAwarePaginator, ticketCount: mixed}
     */
    public function getUserTickets(Request $request): array
    {
        return [
            $this->ticketRepository->getUserTickets($request),
            $this->ticketRepository->userTicketCounts($request)
        ];
    }

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

            if ($replyData->has_attachments) {
                $this->cleanupTemporaryFile($reply, $data->fileData);
            }
            DB::commit();

            return $ticket;
        } catch (\Throwable $th) {
            DB::rollback();

            return $th;
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getAdminTickets(Request $request): array
    {
        return [
            $this->ticketRepository->getTickets($request),
            $this->ticketRepository->ticketCounts($request)
        ];
    }

    public function replyTicket(StoreReplyRequest $request, ReplyData $data): bool|Throwable|Exception{
        try {
            DB::beginTransaction();
            $reply = $this->replyRepository->create($data);

            if ($data->has_attachments) {
                $this->cleanupTemporaryFile($reply, UploadingFileData::fromRequest($request));
            }
            $ticket = $reply->ticket;
            $user = $ticket->submitter;
            CreateNewTicketEvent::dispatch($ticket, $user);
            DB::commit();

            return true;
        } catch (\Throwable $th) {
            DB::rollback();

            return $th;
        }
    }
}
