<?php

namespace App\Repositories\Contracts;

use App\Data\ReplyData;
use App\Models\Reply;

interface ReplyRepositoryInterface
{
    /**
     * @param ReplyData $data
     * @return Reply
     */
    public function create(ReplyData $data): Reply;
}
