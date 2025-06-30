<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyRecource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => new ReplyAuthorResource($this->whenLoaded('author')),
//            'ticket' => new TicketRecource($this->whenLoaded('ticket')),
//            'agent' => $this->whenLoaded('agent'),
            'attachment' => $this->has_attachments ? $this->getMedia('*')[0]->getFullUrl() : false,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'seen_at' => $this->seen_at,
        ];
    }
}
