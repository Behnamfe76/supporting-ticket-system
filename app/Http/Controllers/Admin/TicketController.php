<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tickets\StoreReplyRequest;
use App\Http\Resources\TicketRecource;
use App\Models\Ticket;
use App\Services\Contracts\TicketServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketServiceInterface $ticketService,
    )
    {}

    /**
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function index(Request $request): Response|RedirectResponse
    {
        try {
            [$tickets, $ticketCount] = $this->ticketService->getAdminTickets($request);

            return Inertia::render('Admin/Tickets/Index', [
                "tickets" => $tickets,
                "ticketCount" => $ticketCount,
            ]);

        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return back()->withErrors([
                "message" => "failed to retrieve tickets.",
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket): Response
    {
        return Inertia::render('Admin/Tickets/Show', [
            "ticket" => new TicketRecource($ticket->load(["replies", "submitter"])),
        ]);
    }

    public function reply(StoreReplyRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $this->ticketService->replyTicket($request, $request->toDto($ticket));

            return redirect()->route('admin.tickets.show', ['ticket' => $ticket->slug]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return back()->withErrors([
                "message" => "failed to reply the ticket.",
            ]);
        }
    }
}
