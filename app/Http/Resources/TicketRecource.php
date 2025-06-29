<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketRecource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $submitter = $this->submitter;
        return [
            'slug' => $this->slug,
            'subject' => $this->subject,
            'priority' => $this->priority,
            'status' => $this->status,
            'closed_at' => $this->closed_at,
            'created_at' => $this->created_at,
            'submitter' => [
                'id' => $submitter->id,
                'name' => $submitter->name,
                'email' => $submitter->email,
            ],
            'replies' => ReplyRecource::collection($this->whenLoaded('replies')->load('author')),
        ];
    }
}
