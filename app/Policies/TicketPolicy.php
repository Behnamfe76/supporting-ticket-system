<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ticket.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('ticket.view') && (
            $user->id === $ticket->assignee_id ||
            $user->id === $ticket->user_id ||
            $user->hasRole('admin') ||
            $user->hasRole('super-admin')
        );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('ticket.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('ticket.update') && (
            $user->id === $ticket->assignee_id ||
            $user->id === $ticket->user_id ||
            $user->hasRole('admin') ||
            $user->hasRole('super-admin')
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('ticket.delete') && (
            $user->id === $ticket->assignee_id ||
            $user->id === $ticket->user_id ||
            $user->hasRole('admin') ||
            $user->hasRole('super-admin')
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('ticket.update') && (
            $user->id === $ticket->assignee_id ||
            $user->id === $ticket->user_id ||
            $user->hasRole('admin') ||
            $user->hasRole('super-admin')
        );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('ticket.delete') && (
            $user->id === $ticket->assignee_id ||
            $user->id === $ticket->user_id ||
            $user->hasRole('admin') ||
            $user->hasRole('super-admin')
        );
    }
}
