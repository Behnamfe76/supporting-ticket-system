<?php

namespace App\Data;

use App\Http\Requests\Tickets\StoreReplyRequest;
use App\Models\Ticket;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;

class ReplyData extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $content,

        #[Required, IntegerType]
        public int $ticket_id,

        #[Nullable, IntegerType]
        public null|int $agent_id,

        #[Required, IntegerType]
        public int $author_id,

        #[Required, BooleanType]
        public bool $has_attachments,

        #[Nullable, Date]
        public string|null $seen_at,
    ) {}

    public static function new(TicketData $data, Ticket $ticket ): self{
        return new self(
            content: $data->content,
            ticket_id: $ticket->id,
            agent_id: null,
            author_id: request()->user()->id,
            has_attachments: (bool)$data->fileData,
            seen_at: null,
        );
    }

    public static function fromRequest(StoreReplyRequest $request, Ticket $ticket): ReplyData
    {
        $user = request()->user();
        return new self(
            content: $request->get('content'),
            ticket_id: $ticket->id,
            agent_id: $user->hasAnyRole(['admin', 'super-admin', 'admin']) ? $user->id : null,
            author_id: $user->id,
            has_attachments: (bool)$request->file_data,
            seen_at: null,
        );
    }
}
