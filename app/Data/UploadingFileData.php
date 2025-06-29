<?php

namespace App\Data;

use App\Http\Requests\Tickets\StoreReplyRequest;
use Spatie\LaravelData\Data;
use App\Http\Requests\Tickets\StoreTicketRequest;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;

class UploadingFileData extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $file_name,

        #[Required, StringType]
        public string $path,

        #[Required, IntegerType]
        public int $user_id,
    ) {}

    public static function fromRequest(StoreTicketRequest|StoreReplyRequest $request): self
    {
        return new self(
            file_name: $request->get('file_data')['file_name'],
            path: $request->get('file_data')['path'],
            user_id: $request->user()->id,
        );
    }
}
