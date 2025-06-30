<?php

namespace App\Listeners;

use App\Events\CreateNewTicketEvent;
use App\Notifications\CreateNewTicketNotification;

class SendNewTicketNotification
{
    public function handle(CreateNewTicketEvent $event)
    {
        $event->user->notify(new CreateNewTicketNotification($event->ticket));
    }
}
