<?php

namespace App\Data;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;

class UserData extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $name,

        #[Required, StringType]
        public string $email,

        #[Required, StringType]
        public string $role,

        #[Nullable, Date]
        public string|null $created_at,
    ) {}

    public static function fromRequest(Request $request): UserData
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            role: $request->input('role'),
            created_at: Carbon::now(),
        );
    }
}
