<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Reply;
use Inertia\Response;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\TicketRecource;
use App\Http\Requests\Tickets\StoreReplyRequest;
use App\Http\Requests\Tickets\StoreTicketRequest;
use App\Http\Requests\Tickets\UpdateTicketRequest;
use App\Services\Contracts\TicketServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;

class TicketController extends Controller
{
    protected TicketServiceInterface $ticketService;

    public function __construct(TicketServiceInterface $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response|RedirectResponse
    {
        try {
            [$tickets, $ticketCount] = $this->ticketService->getUserTickets($request);

            return Inertia::render('Tickets/Index', [
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
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Tickets/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        try {
            $ticket = $this->ticketService->createTicket($request->toDto());

            return redirect()->route('tickets.show', ['ticket' => $ticket->slug]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return back()->withErrors(
                ["error" => "failed to create ticket"]
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket): Response
    {
        return Inertia::render('Tickets/Show', [
            "ticket" => new TicketRecource($ticket->load(["replies", "submitter"])),
        ]);
    }

    public function reply(StoreReplyRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $this->ticketService->replyTicket($request, $request->toDto($ticket));

            return redirect()->route('tickets.show', ['ticket' => $ticket->slug]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return back()->withErrors([
                "message" => "failed to reply the ticket.",
            ]);
        }
    }
}
