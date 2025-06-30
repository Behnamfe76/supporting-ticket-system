<?php

namespace App\Factories;

use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Models\Ticket;

class NotificationMailFactory
{
    public static function make(string $type, User $user, $data = []): MailMessage
    {
        switch ($type) {
            case 'new_ticket':
                return self::newTicket($user, $data['ticket']);
            default:
                throw new \InvalidArgumentException("Unknown notification mail type: $type");
        }
    }

    protected static function newTicket(User $user, Ticket $ticket): MailMessage
    {
        $url = url('/tickets/' . $ticket->slug);
        return (new MailMessage)
            ->subject('New Ticket Created')
            ->view('emails.new_ticket_notification', [
                'user' => $user,
                'ticket' => $ticket,
                'url' => $url,
            ]);
    }
}
