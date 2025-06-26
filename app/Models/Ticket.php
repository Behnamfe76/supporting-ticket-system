<?php

namespace App\Models;

use App\Enums\TicketStatus;
use App\Traits\HasSlug;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory, HasSlug;

    protected array $slugSignature = [
        'length' => 10,
        'type'   => 'alphanumeric', // or 'numeric' or 'alpha'
        'prefix' => 'TCK-',
        'suffix' => '',
    ];


    protected $fillable = [
        'assignee_id',
        'slug',
        'status',
        'closed_at',
        'subject',
        'priority'
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    public function casts(): array
    {
        return [
            "status" => TicketStatus::class
        ];
    }
}
