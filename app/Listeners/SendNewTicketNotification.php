<?php

namespace App\Listeners;

use App\Events\CreateNewTicketEvent;
use App\Notifications\CreateNewTicketNotification;

class SendNewTicketNotification
{
    /**
     * @param CreateNewTicketEvent $event
     * @return void
     */
    public function handle(CreateNewTicketEvent $event): void
    {
        $event->user->notify(new CreateNewTicketNotification($event->reply));
    }
}
