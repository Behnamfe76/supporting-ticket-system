<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReplyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('reply.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reply $reply): bool
    {
        return $user->hasPermissionTo('reply.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('reply.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reply $reply): bool
    {
        return $user->hasPermissionTo('reply.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reply $reply): bool
    {
        return $user->hasPermissionTo('reply.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reply $reply): bool
    {
        return $user->hasPermissionTo('reply.update');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reply $reply): bool
    {
        return $user->hasPermissionTo('reply.delete');
    }
}
