<?php

namespace App\Notifications;

use App\Models\Reply;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Factories\NotificationMailFactory;

class CreateNewTicketNotification extends Notification
{
    use Queueable;

    protected Ticket $ticket;
    protected Reply $reply;
    /**
     * Create a new notification instance.
     */
    public function __construct(Reply $reply)
    {
        $this->ticket = $reply->ticket;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database', 'broadcast'];

        if(! request()->user()->hasRole('user')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return NotificationMailFactory::make('new_ticket', $notifiable, ['ticket' => $this->ticket]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket' => $this->ticket,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'ticket_slug' => $this->ticket->slug,
            'ticket_subject' => $this->ticket->subject,
            'reply_id' => $this->reply->id,
        ];
    }
}
