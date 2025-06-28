<?php

namespace App\Data;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;
use App\Enums\TicketPriority;
use App\Http\Requests\Tickets\StoreTicketRequest;
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
}
