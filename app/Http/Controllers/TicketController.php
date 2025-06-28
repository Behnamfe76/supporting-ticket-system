<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tickets\StoreTicketRequest;
use App\Http\Requests\Tickets\UpdateTicketRequest;
use App\Models\Reply;
use App\Models\Ticket;
use App\Services\Contracts\TicketServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    protected TicketServiceInterface $ticketService;

    public function __construct(TicketServiceInterface $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Display a listing of the resource.
     * @throws AuthorizationException
     */
    public function index(): Response
    {
        return Inertia::render('Tickets/Index', []);
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
           "ticket" => $ticket,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }

    public function reply($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $user = auth()->user();
        // Check permission to create a reply
        $this->authorize('create', Reply::class);
        // Optionally, check if user is related to the ticket
        if (!($user->id === $ticket->assignee_id || $user->id === $ticket->user_id || $user->hasRole('admin') || $user->hasRole('super-admin'))) {
            abort(403, 'You are not authorized to reply to this ticket.');
        }
        // ... reply logic ...
    }
}
