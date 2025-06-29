<?php

namespace App\Data;

use App\Enums\TicketStatus;
use Spatie\LaravelData\Data;
use App\Enums\TicketPriority;
use App\Http\Requests\Tickets\StoreTicketRequest;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;

class TicketData extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $subject,

        #[Required, StringType]
        public string $content,

        #[Required]
        public TicketPriority $priority,

        #[Nullable, IntegerType]
        public ?int $assignee_id,

        #[Nullable, IntegerType]
        public ?int $submitted_by,

        #[Required]
        public TicketStatus $status,

        #[Required, Nullable]
        public ?UploadingFileData $fileData,

        #[Nullable]
        public ?string $closed_at = null,
    ) {}

    /**
     * @param StoreTicketRequest $request
     * @return TicketData
     */
    public static function fromRequest(StoreTicketRequest $request): TicketData
    {
        return new self(
            subject: $request->get('subject'),
            content: $request->get('content'),
            priority: TicketPriority::from($request->get('priority')),
            assignee_id: null,
            submitted_by: $request->user()->id,
            status: TicketStatus::OPEN,
            fileData: $request->get('file_data') ? UploadingFileData::fromRequest($request) : null,
            closed_at: null
        );
    }
}
