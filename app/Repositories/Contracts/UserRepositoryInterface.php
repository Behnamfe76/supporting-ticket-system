<?php

namespace App\Repositories\Contracts;

use App\Data\ReplyData;
use App\Models\Reply;

interface UserRepositoryInterface
{
    /**
     * @return mixed
     */
    public function Index(): mixed;
}
