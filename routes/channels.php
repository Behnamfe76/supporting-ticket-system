<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('tickets.reply.user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
