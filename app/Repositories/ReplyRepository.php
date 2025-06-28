<?php

namespace App\Repositories;

use App\Data\ReplyData;
use App\Models\Reply;
use App\Repositories\Contracts\ReplyRepositoryInterface;

class ReplyRepository implements ReplyRepositoryInterface
{
    /**
     * @param ReplyData $data
     * @return Reply
     */
    public function create(ReplyData $data): Reply
    {
        return Reply::create($data->toArray());
    }
}
