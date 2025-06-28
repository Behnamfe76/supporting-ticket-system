<?php

namespace App\Models;

use Database\Factories\ReplyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Reply extends Model implements HasMedia
{
    /** @use HasFactory<ReplyFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'ticket_id',
        'agent_id',
        'author_id',
        'content',
        'has_attachments',
        'seen_at'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }
}
