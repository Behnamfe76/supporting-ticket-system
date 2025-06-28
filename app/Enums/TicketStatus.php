<?php

namespace App\Enums;

enum TicketStatus: string
{
    case OPEN = "open";
    case IN_PROGRESS = "in_progress";
    case CLOSED = "closed";

    /**
     * @param string $str
     * @return self|null
     */
    public static function fromString(string $str): ?self
    {
        return match ($str) {
            'open' => self::OPEN,
            'in_progress' => self::IN_PROGRESS,
            'closed' => self::CLOSED,
            default => null,
        };
    }
}
