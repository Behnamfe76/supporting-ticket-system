<?php

namespace App\Events;

use App\Http\Resources\ReplyRecource;
use App\Models\Reply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class CreateNewTicketEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Reply $reply;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Reply $reply, User $user)
    {
        $this->reply = $reply->load(['author', 'media']);
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tickets.reply.user.' . $this->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ticket.reply.created';
    }
}
